<?php

class ServerController extends BaseController
{
    public function getServers($modpack_slug = null)
    {
        $query_array = [];
        $selected_tags = [];
        $selected_country = false;
        $selected_permission = false;
        $selected_modpack = $modpack_slug;
        $modpack_line = 'for all Modpacks, ';
        $tag_line = 'with any tags, ';
        $country_line = 'in any country, ';
        $permission_line = 'and with any permission.';

        $permissions_array = [
            'whitelist' => "Whitelist",
            'greylist' => "Greylist",
            'open' => "Open"
        ];

        $id_permissions_array = [
            'whitelist' => 1,
            'greylist' => 2,
            'open' => 3
        ];

        $input = Input::only('tags', 'country', 'permission');

        $countries = Server::countryList();
        $tags = ServerTag::all();

        $table_javascript = route('tdf', ['servers', 'all']);

        if (!$modpack_slug) {
            $title = 'Modded Minecraft Servers - ' . $this->site_name;
            $meta_description = 'Modded Minecraft servers for all the packs we list. With powerful searching and filtering.';
            $modpack_name = null;

        } else {
            $modpack = Modpack::where('slug', $modpack_slug)->first();

            $title = $modpack->name . ' Servers - ' . $this->site_name;
            $meta_description = 'Minecraft Servers for the ' . $modpack->name . ' modpack. With powerful searching and filtering.';
            $modpack_name = $modpack->name;
            $modpack_line = 'for ' . $modpack->name . ', ';

            $query_array[] = 'modpack=' . $modpack->id;
        }

        if ($input['tags']) {
            $tags_array = [];
            $tag_names = [];
            $tags_javascript_string = '';
            $tag_line = 'including the tags ';

            foreach ($tags as $t) {
                $tags_array[$t->slug] = $t->id;
                $tag_names[$t->slug] = $t->name;
            }

            $exploded_tags = explode(',', strtolower($input['tags']));

            foreach ($exploded_tags as $t) {
                if (array_key_exists($t, $tags_array)) {
                    $tags_javascript_string .= $tags_array[$t] . ',';
                    $selected_tags[] = $t;
                    $tag_line .= $tag_names[$t] . ', ';
                }
            }
            $tag_line = rtrim($tag_line, ', ') . ', ';
            $query_array[] = 'tags=' . rtrim($tags_javascript_string, ',');
        }

        if ($input['country']) {
            $country_code = $input['country'];
            $query_array[] = 'country=' . $country_code;
            $selected_country = $country_code;
            $country_line = 'in ' . $countries[$country_code] . ', ';
        }

        if ($input['permission']) {
            $permission = $input['permission'];
            $query_array[] = 'permission=' . $id_permissions_array[$permission];
            $selected_permission = $input['permission'];
            $permission_line = 'and with ' . strtolower($permissions_array[$permission]) . ' permissions.';
        }

        $query_count = 0;

        foreach ($query_array as $q) {
            if ($query_count == 0) {
                $table_javascript .= '?';
            } else {
                $table_javascript .= '&';
            }
            $table_javascript .= $q;

            $query_count++;
        }

        $display_line = 'Displaying servers ' . $modpack_line . $tag_line . $country_line . $permission_line;

        return View::make('servers.list', [
            'title' => $title,
            'meta_description' => $meta_description,
            'table_javascript' => $table_javascript,
            'modpack_name' => $modpack_name,
            'countries' => $countries,
            'chosen' => true,
            'permissions' => $permissions_array,
            'selected_tags' => $selected_tags,
            'selected_country' => $selected_country,
            'selected_permission' => $selected_permission,
            'selected_modpack' => $selected_modpack,
            'display_line' => $display_line,

        ]);
    }

    public function postServers()
    {
        $query_array = [];
        $query_string = '';
        $modpack = false;

        $input = Input::only('modpack', 'tags', 'country', 'permission');

        if ($input['modpack']) {
            if ($input['modpack'] != 'any') {
                $modpack = $input['modpack'];
            }
        }

        if ($input['tags']) {
            $tag_string = 'tags=';
            foreach ($input['tags'] as $t) {
                $tag_string .= $t . ',';
            }
            $query_array[] = rtrim($tag_string, ',');
        }

        if ($input['country']) {
            if ($input['country'] != 'all') {
                $query_array[] = 'country=' . $input['country'];
            }
        }

        if ($input['permission']) {
            if ($input['permission'] != 'any') {
                $query_array[] = 'permission=' . $input['permission'];
            }
        }

        $query_count = 0;

        foreach ($query_array as $q) {
            if ($query_count == 0) {
                $query_string .= '?';
            } else {
                $query_string .= '&';
            }
            $query_string .= $q;

            $query_count++;
        }

        if ($modpack) {
            return Redirect::to(action('ServerController@getServers', [$modpack]) . $query_string);
        } else {
            return Redirect::to(action('ServerController@getServers') . $query_string);
        }
    }

    public function getServer($id, $slug)
    {
        $server = Server::find($id);
        $countries = Server::countryList();

        if (!$server) {
            return Redirect::action('ServerController@getServers', [], 301);
        }

        if ($slug != $server->slug) {
            return Redirect::action('ServerController@getServer', [$server->id, $server->slug], 301);;
        }

        $can_edit = false;

        if (Auth::check()) {
            if (Auth::id() == $server->user_id) {
                $can_edit = true;
            }
        }

        $mods_javascript = route('tdf', ['servermods', 'all']) . '?id=' . $server->id;
        $players_javascript = route('tdf', ['serverplayers', 'all']) . '?id=' . $server->id;

        $table_javascript = [
            $mods_javascript,
            $players_javascript
        ];

        $raw_links = [
            'website' => $server->website,
            'application_url' => $server->application_url,
        ];

        $links = [];

        foreach ($raw_links as $index => $link) {
            if ($link != '') {
                $links["$index"] = $link;
            }
        }

        $tags = $server->tags;
        $status = $server->status;
        $modpack = $server->modpack;
        $version = MinecraftVersion::where('id', $modpack->minecraft_version_id)->first();
        $version_slug = preg_replace('/\./', '-', $version->name);
        $country_name = $countries[$server->country];
        $country_code = strtolower($server->country);

        $markdown_html = Parsedown::instance()->setBreaksEnabled(true)->text(strip_tags($server->description));
        $description = str_replace('<table>', '<table class="table table-striped table-bordered">', $markdown_html);

        if ($server->last_world_reset == '0000-00-00') {
            $server->last_world_reset = null;
        }

        if ($server->next_world_reset == '0000-00-00') {
            $server->next_world_reset = null;
        }

        $title = $server->name . ' - ' . $modpack->name . ' Server - ' . $this->site_name;
        $meta_description = $server->deck;

        return View::make('servers.detail', [
            'server' => $server,
            'status' => $status,
            'tags' => $tags,
            'links' => $links,
            'modpack' => $modpack,
            'version' => $version,
            'version_slug' => $version_slug,
            'country_code' => $country_code,
            'country_name' => $country_name,
            'description' => $description,
            'can_edit' => $can_edit,
            'title' => $title,
            'meta_description' => $meta_description,
            'table_javascript' => $table_javascript,
        ]);
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
        /*        if (Auth::check()) {
                    return Redirect::to('/login?return=server/add');
                }*/

        $title = 'Add Server - ' . $this->site_name;

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
            'datepicker' => true,
            'title' => $title,
        ]);
    }

    public function postAdd()
    {
        $logged_in = false;

        if (Auth::check()) {
            $logged_in = true;
        }

        $versions = MinecraftVersion::all();
        $title = 'Add Server - ' . $this->site_name;

        $countries = Server::countryList();

        $permissions = [
            1 => 'Whitelist',
            2 => 'Greylist',
            3 => 'Open',
        ];

        $input = Input::only('name', 'modpack', 'email', 'deck', 'website', 'application_url', 'description', 'slug',
            'server_address_hide', 'player_list_hide', 'motd_hide', 'server_address', 'tags', 'country', 'permissions',
            'last_world_reset', 'next_world_reset', 'active', 'email_alerts', 'g-recaptcha-response');

        $modpack = Modpack::find($input['modpack']);
        $modpack_version = $modpack->version->name;

        if (preg_match('/:/', $input['server_address'])) {
            $exploded_hostname = explode(':', $input['server_address']);
            $server_host = $exploded_hostname[0];
            $server_port = $exploded_hostname[1];
        } else {
            $server_host = $input['server_address'];
            $server_port = 25565;
        }

        $input['server_host'] = $server_host;

        $server_info = Server::check($server_host, $server_port, $modpack_version);

        $validator_messages = [
            'name.unique' => 'A server with this name already exists in the database.',
            'server_host.unique' => 'A server with this address already exists in the database.',
            'country.not_in' => 'The country field is required.',
            'deck.required' => 'The short description field is required.',
            'deck.max' => 'The short description may not be greater than 255 characters.',
            'url' => 'The :attribute field is not a valid URL.',
            'g-recaptcha-response.required' => 'reCAPTCHA verification is required.',
        ];

        $validator_rules = [
            'name' => 'required|unique:servers,name',
            'server_host' => 'required|unique:servers,ip_host,NULL,id,port,' . $server_port,
            'deck' => 'required|max:255',
            'website' => 'url',
            'application_url' => 'url',
            'tags' => 'required',
            'country' => 'required|not_in:choose,separator1,separator2',
            'permissions' => 'required',
            'last_world_reset' => 'date_format:Y-m-d',
            'next_world_reset' => 'date_format:Y-m-d',
        ];

        if (!$logged_in) {
            $validator_rules['email'] = 'required|email';
            $validator_rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        $validator = Validator::make($input, $validator_rules, $validator_messages);

        if (!$server_info) {
            $validator->fails(); //manually fail the validator since we can't reach the server
            $validator->getMessageBag()->add('server_address', 'Unable to reach server.');

            return Redirect::action('ServerController@getAdd')->withErrors($validator)->withInput();
        } elseif ($validator->fails()) {
            return Redirect::action('ServerController@getAdd')->withErrors($validator)->withInput();
        } else {
            $server = new Server;

            $server->modpack_id = $modpack->id;
            $server->minecraft_version_id = $modpack->minecraft_version_id;
            $server->name = $input['name'];
            $server->ip_host = $server_host;
            $server->port = $server_port;
            $server->deck = $input['deck'];
            $server->country = $input['country'];
            $server->permissions = $input['permissions'];
            $server->website = $input['website'];
            $server->application_url = $input['application_url'];
            $server->description = $input['description'];
            $server->last_world_reset = $input['last_world_reset'];
            $server->next_world_reset = $input['next_world_reset'];
            $server->last_check = Carbon\Carbon::now()->toDateTimeString();

            if ($logged_in) {
                if ($input['active'] == 1) {
                    $server->active = 1;
                }
                $server->user_id = Auth::id();
            } else {
                $server->user_id = 0;
            }

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

                if (isset($server_info['players']['online'])) {
                    $server_status->current_players = $server_info['players']['online'];
                } elseif (isset($server_info['Players'])) {
                    $server_status->current_players = $server_info['Players'];
                }

                if (isset($server_info['players']['max'])) {
                    $server_status->max_players = $server_info['players']['max'];
                } elseif (isset($server_info['MaxPlayers'])) {
                    $server_status->max_players = $server_info['MaxPlayers'];
                }

                $server_status->last_success = Carbon\Carbon::now()->toDateTimeString();

                if (isset($server_info['modinfo'])) {
                    $server_status->mods = json_encode($server_info['modinfo']);
                }

                if (isset($server_info['players']['sample'])) {
                    $server_status->players = json_encode($server_info['players']['sample']);
                }

                $success = $server_status->save();

                if ($success) {

                    if (!$logged_in) {
                        $password = str_random(32);

                        $server_user = new ServerUser;

                        $server_user->server_id = $server->id;
                        $server_user->email = $input['email'];
                        $server_user->edit_password = Hash::make($password);
                        $server_user->last_ip = Request::getClientIp();

                        $server_user_success = $server_user->save();

                        if ($server_user_success) {
                            Mail::send('emails.server_add', ['password' => $password, 'server_id' => $server->id],
                                function ($message) use ($input) {
                                    $message->from('noreply@modpackindex.com', 'Modpack Index');
                                    $message->to($input['email'])->subject('Server confirmation for ' . $input['name']);
                                });
                        }
                    }

                    return View::make('servers.add', [
                        'title' => $title,
                        'chosen' => true,
                        'success' => true,
                        'logged_in' => $logged_in,
                        'datepicker' => true,
                        'versions' => $versions,
                        'countries' => $countries,
                        'permissions' => $permissions
                    ]);
                }

                return Redirect::action('ServerController@getAdd')->withErrors(['message' => 'Unable to add server.'])
                    ->withInput();

            } else {
                return Redirect::action('ServerController@getAdd')->withErrors(['message' => 'Unable to add server.'])
                    ->withInput();
            }
        }
    }

    public function getEdit($id, $password = null)
    {
        $selected_tags = [];
        $server_user = false;

        $server = Server::find($id);

        if (!$server) {
            return Redirect::route('index');
        }

        if ($server->user_id == 0) {
            $server_user = $server->serverUser;

            if (!Hash::check($password, $server_user->edit_password)) {
                return Redirect::route('index');
            }
        } else {
            if (!Auth::check()) {
                return Redirect::to('/login?return=server/edit/' . $id);
            }

            if (Auth::id() != $server->user_id && !$this->checkRoute()) {
                return Redirect::route('index');
            }
        }

        $title = 'Edit Server - ' . $this->site_name;

        $versions = MinecraftVersion::all();
        $countries = Server::countryList();

        foreach ($server->tags as $t) {
            $selected_tags[] = $t->id;
        }

        $permissions = [
            1 => 'Whitelist',
            2 => 'Greylist',
            3 => 'Open',
        ];

        if ($server->last_world_reset == '0000-00-00') {
            $server->last_world_reset = null;
        }

        if ($server->next_world_reset == '0000-00-00') {
            $server->next_world_reset = null;
        }

        return View::make('servers.edit', [
            'chosen' => true,
            'versions' => $versions,
            'countries' => $countries,
            'permissions' => $permissions,
            'title' => $title,
            'server' => $server,
            'server_user' => $server_user,
            'password' => $password,
            'selected_tags' => $selected_tags,
            'datepicker' => true,
        ]);

    }

    public function postEdit($id, $password = null)
    {
        $logged_in = false;
        $server_user = false;

        if (Auth::check()) {
            $logged_in = true;
        }

        $server = Server::find($id);

        if (!$server) {
            return Redirect::action('ServerController@getServers');
        }

        if ($server->user_id == 0) {
            $server_user = $server->serverUser;

            if (!Hash::check($password, $server_user->edit_password)) {
                return Redirect::route('index');
            }
        } else {
            if (!Auth::check()) {
                return Redirect::to(action('UserController@getLogin') . '?return=server/edit/' . $id);
            }

            if (Auth::id() != $server->user_id && !$this->checkRoute()) {
                return Redirect::route('index');
            }
        }

        $versions = MinecraftVersion::all();
        $title = 'Edit Server - ' . $this->site_name;

        $countries = Server::countryList();

        $permissions = [
            1 => 'Whitelist',
            2 => 'Greylist',
            3 => 'Open',
        ];

        $input = Input::only('name', 'modpack', 'email', 'deck', 'website', 'application_url', 'description', 'slug',
            'server_address_hide', 'player_list_hide', 'motd_hide', 'server_address', 'selected_tags', 'country',
            'permissions', 'last_world_reset', 'next_world_reset', 'active', 'email_alerts');

        $modpack = Modpack::find($input['modpack']);
        $modpack_version = $modpack->version->name;

        if (preg_match('/:/', $input['server_address'])) {
            $exploded_hostname = explode(':', $input['server_address']);
            $server_host = $exploded_hostname[0];
            $server_port = $exploded_hostname[1];
        } else {
            $server_host = $input['server_address'];
            $server_port = 25565;
        }

        $input['server_host'] = $server_host;

        $server_info = Server::check($server_host, $server_port, $modpack_version);

        $validator_messages = [
            'name.unique' => 'A server with this name already exists in the database.',
            'server_host.unique' => 'A server with this address already exists in the database.',
            'country.not_in' => 'The country field is required.',
            'deck.required' => 'The short description field is required.',
            'deck.max' => 'The short description may not be greater than 255 characters.',
            'url' => 'The :attribute field is not a valid URL.',
        ];

        $validator_rules = [
            'name' => 'required|unique:servers,name,' . $server->id,
            'server_host' => 'required|unique:servers,ip_host,' . $server->id . ',id,port,' . $server_port,
            'deck' => 'required|max:255',
            'website' => 'url',
            'application_url' => 'url',
            'selected_tags' => 'required',
            'country' => 'required|not_in:choose,separator1,separator2',
            'permissions' => 'required',
            'last_world_reset' => 'date_format:Y-m-d',
            'next_world_reset' => 'date_format:Y-m-d',
        ];

        if (!$logged_in) {
            $validator_rules['email'] = 'required|email';
        }

        $validator = Validator::make($input, $validator_rules, $validator_messages);

        if (!$server_info) {
            $validator->fails(); //manually fail the validator since we can't reach the server
            $validator->getMessageBag()->add('server_address', 'Unable to reach server.');

            if (!$logged_in) {
                return Redirect::action('ServerController@getEdit', [$id, $password])->withErrors($validator)
                    ->withInput();
            }

            return Redirect::action('ServerController@getEdit', [$id])->withErrors($validator)->withInput();
        } elseif ($validator->fails()) {
            if (!$logged_in) {
                return Redirect::action('ServerController@getEdit', [$id, $password])->withErrors($validator)
                    ->withInput();
            }

            return Redirect::action('ServerController@getEdit', [$id])->withErrors($validator)->withInput();
        } else {
            $server->modpack_id = $modpack->id;
            $server->user_id = Auth::id();
            $server->minecraft_version_id = $modpack->minecraft_version_id;
            $server->name = $input['name'];
            $server->ip_host = $server_host;
            $server->port = $server_port;
            $server->deck = $input['deck'];
            $server->country = $input['country'];
            $server->permissions = $input['permissions'];
            $server->website = $input['website'];
            $server->application_url = $input['application_url'];
            $server->description = $input['description'];
            $server->last_world_reset = $input['last_world_reset'];
            $server->next_world_reset = $input['next_world_reset'];
            $server->last_check = Carbon\Carbon::now()->toDateTimeString();

            $server->active = 0;
            if ($input['active'] == 1) {
                $server->active = 1;
            }

            $server->email_alerts = 0;
            if ($input['email_alerts'] == 1) {
                $server->email_alerts = 1;
            }

            $server->server_address_hide = 0;
            if ($input['server_address_hide'] == 1) {
                $server->server_address_hide = 1;
            }

            $server->player_list_hide = 0;
            if ($input['player_list_hide'] == 1) {
                $server->player_list_hide = 1;
            }

            $server->motd_hide = 0;
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
                foreach ($server->tags as $t) {
                    $server->tags()->detach($t->id);
                }
                $server->tags()->attach($input['selected_tags']);

                $server_status = ServerStatus::where('server_id', $server->id)->first();

                $server_status->server_id = $server->id;

                if (isset($server_info['players']['online'])) {
                    $server_status->current_players = $server_info['players']['online'];
                } elseif (isset($server_info['Players'])) {
                    $server_status->current_players = $server_info['Players'];
                }

                if (isset($server_info['players']['max'])) {
                    $server_status->max_players = $server_info['players']['max'];
                } elseif (isset($server_info['MaxPlayers'])) {
                    $server_status->max_players = $server_info['MaxPlayers'];
                }

                $server_status->last_success = Carbon\Carbon::now()->toDateTimeString();

                if (isset($server_info['modinfo'])) {
                    $server_status->mods = json_encode($server_info['modinfo']);
                }

                if (isset($server_info['players']['sample'])) {
                    $server_status->players = json_encode($server_info['players']['sample']);
                }

                $success = $server_status->save();

                if ($success) {
                    if (!$logged_in) {

                        $server_user = ServerUser::where('server_id', $server->id)->first();

                        $server_user->email = $input['email'];
                        $server_user->last_ip = Request::getClientIp();
                    }

                    $updated_server = Server::find($id);

                    foreach ($updated_server->tags as $t) {
                        $selected_tags[] = $t->id;
                    }

                    if ($updated_server->last_world_reset == '0000-00-00') {
                        $updated_server->last_world_reset = null;
                    }

                    if ($updated_server->next_world_reset == '0000-00-00') {
                        $updated_server->next_world_reset = null;
                    }

                    return View::make('servers.edit', [
                        'chosen' => true,
                        'versions' => $versions,
                        'countries' => $countries,
                        'permissions' => $permissions,
                        'title' => $title,
                        'server' => $updated_server,
                        'server_user' => $server_user,
                        'password' => $password,
                        'selected_tags' => $selected_tags,
                        'success' => true,
                        'datepicker' => true,
                    ]);
                } else {
                    if (!$logged_in) {
                        return Redirect::action('ServerController@getEdit', [$id, $password])
                            ->withErrors(['message' => 'Unable to edit server.'])->withInput();
                    }

                    return Redirect::action('ServerController@getEdit', [$id])
                        ->withErrors(['message' => 'Unable to edit server.'])->withInput();
                }
            } else {
                if (!$logged_in) {
                    return Redirect::action('ServerController@getEdit', [$id, $password])
                        ->withErrors(['message' => 'Unable to edit server.'])->withInput();
                }

                return Redirect::action('ServerController@getEdit', [$id])
                    ->withErrors(['message' => 'Unable to edit server.'])->withInput();
            }
        }
    }

    public function getConfirm($id, $password)
    {
        $error = 'Unable to activate server!';
        $server = Server::find($id);

        if (!$server) {
            App::abort(404);
        }

        $server_user = $server->serverUser;

        if (Hash::check($password, $server_user->edit_password)) {
            if ($server_user->is_confirmed) {
                $error = 'Server is already confirmed!';
            } else {
                $server_user->is_confirmed = 1;
                $server->active = 1;

                $server->save();
                $server_user->save();

                $error = false;
            }
        }

        $title = 'Server Confirmation - ' . $this->site_name;

        return View::make('servers.confirmed', [
            'server' => $server,
            'error' => $error,
            'title' => $title,
        ]);
    }
}