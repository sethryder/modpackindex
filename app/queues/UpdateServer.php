<?php

class UpdateServer
{
    public function fire($job, $data)
    {
        Config::set('services.mandrill.secret', 'J3gPgh8LNHx4Paw-a7j26g'); //since we are outside of nginx, no env variables
        $cache_key = 'server-update-version-cache';

        if (Cache::has($cache_key)) {
            $versions = Cache::get($cache_key);
        } else {
            $versions = [];
            $raw_versions = MinecraftVersion::select('id', 'name')->get();

            foreach ($raw_versions as $v) {
                $key = $v->id;
                $versions[$key] = $v->name;
            }

            Cache::put($cache_key, $versions, 60);
        }

        $server = Server::find($data['server_id']);
        $server_status = ServerStatus::where('server_id', $server->id)->first();

        $server_version_id = $server->minecraft_version_id;
        $version = $versions[$server_version_id];

        $server_query = Server::check($server->ip_host, $server->port, $version);
        $current_timestamp = Carbon\Carbon::now()->toDateTimeString();

        if ($server_query) {
            if (isset($server_query['players']['online'])) {
                $server_status->current_players = $server_query['players']['online'];
            } elseif (isset($server_query['Players'])) {
                $server_status->current_players = $server_query['Players'];
            }

            if (isset($server_query['players']['max'])) {
                $server_status->max_players = $server_query['players']['max'];
            } elseif (isset($server_query['MaxPlayers'])) {
                $server_status->max_players = $server_query['MaxPlayers'];
            }

            if (isset($server_query['modinfo'])) {
                $server_status->mods = json_encode($server_query['modinfo']);
            }

            if (isset($server_query['players']['sample'])) {
                $server_status->players = json_encode($server_query['players']['sample']);
            }

            $server_status->failed_attempts = 0;
            $server_status->failed_checks = 0;
            $server_status->last_success = $current_timestamp;
        } else {
            $server_status->failed_attempts = $server_status->failed_attempts + 1;
            $server_status->last_failure = $current_timestamp;

            if ($server_status->failed_attempts >= Config::get('app.server_failed_attempts')) {
                $server_status->failed_checks = $server_status->failed_checks + 1;
                $server_status->total_failed_checks = $server_status->total_failed_checks + 1;
                $server_status->failed_attempts = 0;
            }

            if ($server_status->failed_checks >= Config::get('app.server_failed_disable')) {
                $server->active = 0;
                $server_status->current_players = 0;
                $server_status->max_players = 0;

                if ($server->email_alerts) {
                    if ($server->user_id == 0) {
                        $server_user = ServerUser::where('server_id', $server->id);

                        $message_array = [
                            'server_name' => $server->name,
                            'username' => $server_user->email,
                            'email' => $server_user->email
                        ];
                    } else {
                        $message_array = [
                            'server_name' => $server->name,
                            'username' => $server->user->username,
                            'email' => $server->user->email
                        ];
                    }

                    Mail::send('emails.disabled_server', array('server' => $server, 'site_url' => Config::get('app.url')),
                        function ($message) use ($message_array) {
                            $message->from('noreply@modpackindex.com', 'Modpack Index');
                            $message->to($message_array['email'],
                                $message_array['username'])->subject('Server Deactivated: ' . $message_array['server_name']);
                        });
                }
            }
        }

        $server_status->total_checks = $server_status->total_checks + 1;
        $server_status->last_check = $current_timestamp;
        $server_status->save();

        $server->queued = 0;
        $server->last_check = $current_timestamp;
        $server->save();

        $job->delete();
    }
}