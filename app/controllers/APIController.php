<?php

Class APIController extends BaseController
{
    use \App\TraitCommon;

    public function getModpacks($version = 'all')
    {
        $modpacks = [];
        $limit = 100;
        $offset = 0;

        $input = Input::only('limit', 'offset');

        if ($input['limit']) {
            $limit = $input['limit'];
        }

        if ($input['offset']) {
            $offset = $input['offset'];
        }

        if ($version == 'all') {
            $raw_modpacks = Modpack::select('id', 'name', 'deck', 'website', 'download_link', 'wiki_link',
                'description', 'slug', 'created_at', 'updated_at')->skip($offset)->take($limit)->get();

            $modpack_count = Modpack::select('id')->count();
        } else {
            $version = $this->getVersion($version);
            $raw_version = MinecraftVersion::where('name', '=', $version)->first();

            if (!$raw_version) {
                return Response::json(['error' => 'Not a valid version.']);
            }

            $version_id = $raw_version->id;
            $raw_modpacks = Modpack::select('id', 'name', 'deck', 'website', 'download_link', 'donate_link',
                'wiki_link', 'description', 'slug', 'created_at', 'updated_at')
                ->where('minecraft_version_id', $version_id)->skip($offset)->take($limit)->get();

            $modpack_count = Modpack::select('id')->where('minecraft_version_id', $version_id)->count();
        }

        if (!$raw_modpacks) {
            return Response::json(['error' => 'No results.']);
        }

        foreach ($raw_modpacks as $modpack) {
            $modpacks['results'][] = [
                'id' => $modpack->id,
                'name' => $modpack->name,
                'short_description' => $modpack->deck,
                'website' => $modpack->website,
                'download_link' => $modpack->download_link,
                'donate_link' => $modpack->website,
                'wiki_link' => $modpack->wiki_link,
                'description' => $modpack->description,
                'slug' => $modpack->slug,
                'created_at' => $modpack->created_at,
                'updated_at' => $modpack->updated_at,
            ];
        }

        $modpacks['meta'] = [
            'total_results' => $modpack_count,
            'limit' => $limit,
            'offset' => $offset
        ];

        return Response::json($modpacks);
    }

    public function getModpack($id)
    {
        $mods = [];

        $raw_modpack = Modpack::find($id);

        if (!$raw_modpack) {
            return Response::json(['error' => 'No modpack with that ID found.']);
        }

        $raw_mods = $raw_modpack->mods;

        foreach ($raw_mods as $mod) {
            $mods[] = [
                'id' => $mod->id,
                'name' => $mod->name,
                'short_description' => $mod->deck,
                'website' => $mod->website,
                'download_link' => $mod->download_link,
                'donate_link' => $mod->website,
                'wiki_link' => $mod->wiki_link,
                'description' => $mod->description,
                'slug' => $mod->slug,
                'created_at' => $mod->created_at,
                'updated_at' => $mod->updated_at,
            ];
        }

        $result = [
            'id' => $raw_modpack->id,
            'name' => $raw_modpack->name,
            'short_description' => $raw_modpack->deck,
            'website' => $raw_modpack->website,
            'download_link' => $raw_modpack->download_link,
            'donate_link' => $raw_modpack->website,
            'wiki_link' => $raw_modpack->wiki_link,
            'description' => $raw_modpack->description,
            'mods' => $mods,
            'slug' => $raw_modpack->slug,
            'created_at' => $raw_modpack->created_at,
            'updated_at' => $raw_modpack->updated_at,
        ];

        return Response::json($result);
    }

    public function getMods($version = 'all')
    {
        $mods = [];

        $limit = 100;
        $offset = 0;

        $input = Input::only('limit', 'offset');

        if ($input['limit']) {
            $limit = $input['limit'];
        }
        if ($input['offset']) {
            $offset = $input['offset'];
        }

        if ($version == 'all') {
            $raw_mods = Mod::select('id', 'name', 'deck', 'website', 'download_link', 'wiki_link', 'description',
                'slug', 'created_at', 'updated_at')->skip($offset)->take($limit)->get();

            $mod_count = Mod::select('id')->count();
        } else {
            $version = $this->getVersion($version);
            $raw_version = MinecraftVersion::where('name', '=', $version)->first();
            $version_id = $raw_version->id;

            if (!$raw_version) {
                return Response::json(['error' => 'Not a valid version.']);
            }

            $query = Mod::whereHas('versions', function ($q) use ($version_id) {
                $q->where('minecraft_versions.id', '=', $version_id);
            });

            $mod_count = $query->count();
            $query->skip($offset)->take($limit);
            $raw_mods = $query->get();
        }

        if (!$raw_mods) {
            return Response::json(['error' => 'No results.']);
        }

        foreach ($raw_mods as $mod) {
            $mods['results'][] = [
                'id' => $mod->id,
                'name' => $mod->name,
                'short_description' => $mod->deck,
                'website' => $mod->website,
                'download_link' => $mod->download_link,
                'donate_link' => $mod->website,
                'wiki_link' => $mod->wiki_link,
                'description' => $mod->description,
                'slug' => $mod->slug,
                'created_at' => $mod->created_at,
                'updated_at' => $mod->updated_at,
            ];
        }

        $mods['meta'] = [
            'total_results' => $mod_count,
            'limit' => $limit,
            'offset' => $offset
        ];

        return Response::json($mods);
    }

    public function getMod($id)
    {
        $modpacks = [];

        $raw_mod = Mod::find($id);

        if (!$raw_mod) {
            return Response::json(['error' => 'No mod with that ID found.']);
        }

        $raw_modpacks = $raw_mod->modpacks;

        foreach ($raw_modpacks as $modpack) {
            $modpacks[] = [
                'id' => $modpack->id,
                'name' => $modpack->name,
                'short_description' => $modpack->deck,
                'website' => $modpack->website,
                'download_link' => $modpack->download_link,
                'donate_link' => $modpack->website,
                'wiki_link' => $modpack->wiki_link,
                'descriptions' => $modpack->descriptions,
                'slug' => $modpack->slug,
                'created_at' => $modpack->created_at,
                'updated_at' => $modpack->updated_at,
            ];
        }

        $result = [
            'id' => $raw_mod->id,
            'name' => $raw_mod->name,
            'short_description' => $raw_mod->deck,
            'website' => $raw_mod->website,
            'download_link' => $raw_mod->download_link,
            'donate_link' => $raw_mod->website,
            'wiki_link' => $raw_mod->wiki_link,
            'descriptions' => $raw_mod->descriptions,
            'modpacks' => $modpacks,
            'slug' => $raw_mod->slug,
            'created_at' => $raw_mod->created_at,
            'updated_at' => $raw_mod->updated_at,
        ];

        return Response::json($result);
    }

    public function getServers($modpack ='all')
    {
        $servers = [];
        $limit = 100;
        $offset = 0;
        $active = 0;

        $input = Input::only('limit', 'offset', 'active');

        if ($input['active']) {
            $active = 1;
        }

        if ($input['limit']) {
            $limit = $input['limit'];
        }

        if ($input['offset']) {
            $offset = $input['offset'];
        }

        if ($modpack == 'all') {
            $raw_servers = Server::select('id', 'modpack_id', 'name', 'ip_host', 'port', 'permissions', 'country', 'deck',
                'description', 'website', 'application_url', 'slug', 'active', 'last_check', 'server_address_hide',
                'player_list_hide', 'last_world_reset', 'next_world_reset', 'created_at', 'updated_at')
                ->where('active', '>=', $active)
                ->skip($offset)->take($limit)->get();

            $server_count = Server::select('id')->where('active', $active)->count();
        } else {
            $raw_servers = Server::select('id', 'modpack_id', 'name', 'ip_host', 'port', 'permissions', 'country', 'deck',
                'description', 'website', 'application_url', 'slug', 'active', 'last_check', 'server_address_hide',
                'player_list_hide', 'last_world_reset', 'next_world_reset', 'created_at', 'updated_at')
                ->where('active', '>=', $active)
                ->where('modpack_id', $modpack)
                ->skip($offset)->take($limit)->get();

            $server_count = Server::select('id')->where('active', $active)->where('modpack_id', $modpack)->count();
        }

        if (!$raw_servers) {
            return Response::json(['error' => 'No results.']);
        }

        foreach ($raw_servers as $server) {
            if ($server->server_address_hide) {
                $server_address = 'Hidden';
            } else {
                $server_address = $server->ip_host . ':' . $server->port;
            }

            $servers['results'][] = [
                'id' => $server->id,
                'modpack_id' => $server->modpack_id,
                'name' => $server->name,
                'short_description' => $server->deck,
                'server_address' => $server_address,
                'country' => $server->country,
                'permissions' => $server->permissions,
                'website' => $server->wiki_link,
                'application_url' => $server->description,
                'description' => $server->slug,
                'server_address_hide' => $server->server_address_hide,
                'player_list_hide' => $server->player_list_hide,
                'active' => $server->active,
                'slug' => $server->slug,
                'last_world_reset' => $server->last_world_reset,
                'next_world_reset' => $server->next_world_reset,
                'created_at' => $server->created_at,
                'updated_at' => $server->updated_at,
            ];
        }

        $servers['meta'] = [
            'total_results' => $server_count,
            'limit' => $limit,
            'offset' => $offset
        ];

        return Response::json($servers);
    }

    public function getServer($id)
    {
        $players_array = [];
        $mods_array = [];

        $server = Server::find($id);

        if (!$server) {
            return Response::json(['error' => 'No mod with that ID found.']);
        }

        $server_status = ServerStatus::where('server_id', $id)->first();

        if (!$server->player_list_hide) {
            $raw_players = json_decode($server_status->players);

            if ($raw_players) {
                foreach ($raw_players as $player) {
                    $players_array[] = [
                        'name' => preg_replace('/\x{00A7}.{1}/u', '', $player->name)
                    ];
                }
            }
        }

        if ($server_status->mods) {
            $raw_mods = json_decode($server_status->mods);

            foreach ($raw_mods->modList as $mod) {
                $mods_array[] = [
                    'name' => htmlspecialchars($mod->modid),
                    'version' => $mod->version,
                ];
            }
        }

        if ($server->server_address_hide) {
            $server_address = 'Hidden';
        } else {
            $server_address = $server->ip_host . ':' . $server->port;
        }

        $result = [
            'id' => $server->id,
            'modpack_id' => $server->modpack_id,
            'name' => $server->name,
            'short_description' => $server->deck,
            'server_address' => $server_address,
            'country' => $server->country,
            'permissions' => $server->permissions,
            'website' => $server->wiki_link,
            'application_url' => $server->description,
            'description' => $server->slug,
            'players' => $players_array,
            'mods' => $mods_array,
            'server_address_hide' => $server->server_address_hide,
            'player_list_hide' => $server->player_list_hide,
            'slug' => $server->slug,
            'last_world_reset' => $server->last_world_reset,
            'next_world_reset' => $server->next_world_reset,
            'created_at' => $server->created_at,
            'updated_at' => $server->updated_at,
        ];

        return Response::json($result);
    }

    public function getStreams($modpack = 'all')
    {
        $streams = [];
        $limit = 100;
        $offset = 0;

        $input = Input::only('limit', 'offset');

        if ($input['limit']) {
            $limit = $input['limit'];
        }

        if ($input['offset']) {
            $offset = $input['offset'];
        }

        if ($modpack == 'all') {
            $raw_streams = TwitchStream::skip($offset)->take($limit)->get();
            $stream_count = TwitchStream::select('id')->count();
        } else {
            $raw_streams = TwitchStream::where('modpack_id', $modpack)->skip($offset)->take($limit)->get();
            $stream_count = TwitchStream::select('id')->where('modpack_id', $modpack)->count();
        }

        if (!$raw_streams) {
            return Response::json(['error' => 'No results.']);
        }

        foreach ($raw_streams as $stream) {
            $streams['results'][] = [
                'channel_id' => $stream->channel_id,
                'modpack_id' => $stream->modpack_id,
                'status' => $stream->status,
                'display_name' => $stream->display_name,
                'language' => $stream->language,
                'preview_image_url' => $stream->preview,
                'twitch_url' => $stream->url,
                'viewers' => $stream->viewers,
                'followers' => $stream->followers,
                'created_at' => $stream->created_at,
                'updated_at' => $stream->updated_at,
            ];
        }

        $streams['meta'] = [
            'total_results' => $stream_count,
            'limit' => $limit,
            'offset' => $offset
        ];

        return Response::json($streams);
    }

    public function getStream($id)
    {
        $stream = TwitchStream::find($id);

        if (!$stream) {
            return json_encode(['error' => 'No stream with that ID is currently live.']);
        }

        $results = [
            'channel_id' => $stream->channel_id,
            'modpack_id' => $stream->modpack_id,
            'status' => $stream->status,
            'display_name' => $stream->display_name,
            'language' => $stream->language,
            'preview_image_url' => $stream->preview,
            'twitch_url' => $stream->url,
            'viewers' => $stream->viewers,
            'followers' => $stream->followers,
            'created_at' => $stream->created_at,
            'updated_at' => $stream->updated_at,
        ];

        return Response::json($results);
    }
}