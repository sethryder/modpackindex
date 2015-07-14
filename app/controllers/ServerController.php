<?php

class ServerController extends BaseController
{
    public function getServers($modpack_slug = null)
    {
        if (!$modpack_slug) {
            $title = 'Modded Servers - ' . $this->site_name;
            $modpack_name = null;

            $table_javascript = '/api/table/servers_all.json';
        } else {
            $modpack = Modpack::where('slug', $modpack_slug)->first();

            $title = $modpack->name . ' Servers - ' . $this->site_name;
            $modpack_name = $modpack->name;

            $table_javascript = '/api/table/servers_all.json?modpacks=' . $modpack->id;
        }

        return View::make('servers.list', [
            'title' => $title,
            'table_javascript' => $table_javascript,
            'modpack_name' => $modpack_name,
        ]);
    }

    public function getServer($id)
    {

    }

    public function getQuery()
    {
        $input = Input::only('ip', 'port', 'version');
        $server = false;

        if (!$input['version']) {
            $input['version'] = '1.7.10';
        }

        try {
            $query = new MinecraftPing($input['ip'], $input['port']);

            if ($input['version'] > '1.7') {
                $server = $query->Query();
            } else {
                $server = $query->QueryOldPre17();
            }
        } catch (xPaw\MinecraftPingException $e) {
        }

        return View::make('servers.query', ['server' => $server]);

    }

    public function postQuery()
    {

    }

    public function getAdd()
    {
        //if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Server - ' . $this->site_name;

        $versions = MinecraftVersion::all();
        $countries = Server::countryList();

        $permissions = [
            1 => 'Whitelist',
            2 => 'Greylist',
            3 => 'Open',
        ];

        return View::make('servers.add', [
            'chosen' => true,
            'versions' => $versions,
            'countries' => $countries,
            'permissions' => $permissions,
            'title' => $title,
        ]);
    }

    public function postAdd()
    {
        //if (!$this->checkRoute()) return Redirect::to('/');
        $versions = MinecraftVersion::all();
        $title = 'Add Server - ' . $this->site_name;

        $countries = Server::countryList();

        $permissions = [
            1 => 'Whitelist',
            2 => 'Greylist',
            3 => 'Open',
        ];

        $input = Input::only('name', 'modpack', 'deck', 'website', 'description', 'slug', 'server_address_hide',
            'player_list_hide', 'motd_hide', 'server_address', 'tags', 'country', 'permissions', 'active', 'email_alerts');

        $messages = [
            'unique' => 'This server already exists in the database.',
            'url' => '',
        ];

        $modpack = Modpack::find($input['modpack']);
        $modpack_version = $modpack->version;

        if (preg_match('/:/', $input['server_address'])) {
            $exploded_hostname = explode(':', $input['server_address']);
            $server_host = $exploded_hostname[0];
            $server_port = $exploded_hostname[1];
        } else {
            $server_host = $input['server_address'];
            $server_port = 25565;
        }

        $server_info = Server::check($server_host, $server_port, $modpack_version);

        //print_r(json_encode($server_info));
        //return 'hi';

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:mods,name',
                'server_address' => 'required|unique:servers,ip_host',
                'deck' => 'required',
                'tags' => 'required',
                'country' => 'required',
                'permissions' => 'required',
            ],
            $messages);

        if (!$server_info) {
            $validator->fails(); //manually fail the validator since we can't reach the server
            $validator->getMessageBag()->add('server_address', 'Unable to reach server.');

            return Redirect::to('/server/add/')->withErrors($validator)->withInput();
        } elseif ($validator->fails()) {
            return Redirect::to('/server/add/')->withErrors($validator)->withInput();
        } else {
            $server = new Server;

            $server->modpack_id = $modpack->id;
            //$server->user_id = Auth::id();
            $server->minecraft_version_id = $modpack->minecraft_version_id;
            $server->user_id = 1;
            $server->name = $input['name'];
            $server->ip_host = $server_host;
            $server->port = $server_port;
            $server->deck = $input['deck'];
            $server->country = $input['country'];
            $server->permissions = $input['permissions'];
            $server->website = $input['website'];
            $server->description = $input['description'];
            $server->last_check = Carbon\Carbon::now()->toDateTimeString();
            $server->active = 1;

            if ($input['email_alerts'] == 1) {
                $server->email_alerts = 1;
            }

            if ($input['server_address_hide'] == 1) {
                $server->server_address_hide = 1;
            }
            if ($input['player_list_hide'] == 1) {
                $server->player_list_hide = 1;
            }
            if ($input['motd_hide'] == 1) {
                $server->motd_hide = 1;
            }

            if ($input['slug'] == '') {
                $slug = Str::slug($input['name']);
            } else {
                $slug = $input['slug'];
            }

            $server->slug = $slug;
            $server->last_ip = Request::getClientIp();

            $success = $server->save();


            if ($success) {
                foreach ($input['tags'] as $tag) {
                    $server->tags()->attach($tag);
                }

                $server_status = new ServerStatus;

                $server_status->server_id = $server->id;
                $server_status->current_players = $server_info['players']['online'];
                $server_status->max_players = $server_info['players']['max'];
                $server_status->mods = json_encode($server_info['modinfo']);
                $server_status->last_success = Carbon\Carbon::now()->toDateTimeString();

                if (isset($server_info['players']['sample'])) {
                    $server_status->players = json_encode($server_info['players']['sample']);
                }

                $success = $server_status->save();

                if ($success) {
                    return View::make('servers.add', [
                        'title' => $title,
                        'chosen' => true,
                        'success' => true,
                        'versions' => $versions,
                        'countries' => $countries,
                        'permissions' => $permissions
                    ]);
                } else {
                    return Redirect::to('/server/add/')->withErrors(['message' => 'Unable to add server.'])->withInput();
                }
            } else {
                return Redirect::to('/server/add/')->withErrors(['message' => 'Unable to add server.'])->withInput();
            }
        }
    }

    public function getEdit($id)
    {

    }

    public function postEdit()
    {

    }

    public function getTest()
    {
        //use xPaw\MinecraftPing;
        //use xPaw\MinecraftPingException;

        $input = Input::only('ip', 'port');
        $input['version'] = '1.7.10';


        try {
            $query = new MinecraftPing($input['ip'], $input['port']);

            if ($input['version'] > '1.7') {
                print_r($query->Query());
            } else {
                print_r($query->QueryOldPre17());
            }
        } catch (xPaw\MinecraftPingException $e) {
        }

    }
}