<?php

class JSONController extends BaseController
{
    use \App\TraitCommon;

    public function getTableMods($version = 'all')
    {
        $cache_key = 'table-mods-' . $version;

        if (Cache::tags('mods')->has($cache_key)) {
            $mods_array = Cache::tags('mods')->get($cache_key);
        } else {
            $mods_array = [];
            $mod_id_array = [];
            $version = $this->getVersion($version);

            if ($version == 'all') {
                $raw_mods = Mod::with('versions')->with('authors')->get();
            } else {
                $raw_version = MinecraftVersion::where('name', '=', $version)->with('mods')->first();
                $raw_mods = $raw_version->mods()->with('authors')->with('versions')->get();
            }

            foreach ($raw_mods as $mod) {
                if ($mod->mod_list_hide == 1) {
                    continue;
                }

                if (in_array($mod->id, $mod_id_array)) {
                    continue;
                }

                $mods_array[] = $this->buildModArray($mod);
                $mod_id_array[] = $mod->id;
            }

            Cache::tags('mods')->forever($cache_key, $mods_array);
        }

        return $this->buildDTModOutput($mods_array);
    }

    public function getTableModpacks($get_version = 'all')
    {
        $cache_key = 'table-modpacks-' . $get_version;

        if (Cache::tags('modpacks')->has($cache_key)) {
            $modpacks_array = Cache::tags('modpacks')->get($cache_key);
        } else {
            $modpacks_array = [];
            $modpack_id_array = [];
            $get_version = $this->getVersion($get_version);

            if ($get_version == 'all') {
                $raw_modpacks = Modpack::with('creators')->with('version')->with('launcher')->get();
            } else {
                $raw_version = MinecraftVersion::where('name', '=', $get_version)->with('modpacks')->first();
                $raw_modpacks = $raw_version->modpacks()->with('creators')->with('version')->with('launcher')->get();
            }

            foreach ($raw_modpacks as $modpack) {
                if (in_array($modpack->id, $modpack_id_array)) {
                    continue;
                }

                $modpacks_array[] = $this->buildModpackArray($modpack);
                $mod_id_array[] = $modpack->id;
            }

            Cache::tags('modpacks')->forever($cache_key, $modpacks_array);
        }

        return $this->buildDTModpackOutput($modpacks_array);
    }

    public function getTableLaunchers($name, $get_version = 'all')
    {
        $cache_key = 'table-launchers-' . $name . '-' . $get_version;

        if (Cache::tags('launchers')->has($cache_key)) {
            $modpacks_array = Cache::tags('launchers')->get($cache_key);
        } else {
            $modpacks_array = [];
            $modpack_id_array = [];
            $get_version = $this->getVersion($get_version);
            $launcher = Launcher::where('slug', '=', $name)->first();
            $launcher_id = $launcher->id;

            if ($get_version == 'all') {
                $raw_modpacks = Modpack::where('launcher_id', '=', $launcher_id)->with('creators')->with('version')
                    ->with('launcher')->get();
            } else {
                $version = MinecraftVersion::where('name', '=', $get_version)->first();
                $version_id = $version->id;

                $raw_modpacks = Modpack::where('launcher_id', '=', $launcher_id)
                    ->where('minecraft_version_id', '=', $version_id)->with('creators')->with('launcher')
                    ->with('version')->get();
            }

            foreach ($raw_modpacks as $modpack) {
                if (in_array($modpack->id, $modpack_id_array)) {
                    continue;
                }

                $modpacks_array[] = $this->buildModpackArray($modpack);
                $mod_id_array[] = $modpack->id;
            }

            Cache::tags('launchers')->forever($cache_key, $modpacks_array);
        }

        return $this->buildDTLauncherOutput($modpacks_array);
    }

    public function getTableModpackMods($name)
    {
        $cache_key = 'table-modpackmods-' . $name;

        if (Cache::tags('modpackmods')->has($cache_key)) {
            $mods_array = Cache::tags('modpackmods')->get($cache_key);
        } else {
            $mods_array = [];
            $mod_id_array = [];
            $modpack = Modpack::where('slug', '=', $name)->first();
            $raw_mods = $modpack->mods()->with('authors')->with('versions')->get();

            foreach ($raw_mods as $mod) {
                if (in_array($mod->id, $mod_id_array)) {
                    continue;
                }

                $mods_array[] = $this->buildModArray($mod);
                $mod_id_array[] = $mod->id;
            }

            Cache::tags('modpackmods')->forever($cache_key, $mods_array);
        }

        return $this->buildDTModOutput($mods_array);
    }

    public function getModModpacks($name)
    {
        $cache_key = 'table-modmodpacks-' . $name;

        if (Cache::tags('modmodpacks')->has($cache_key)) {
            $modpacks_array = Cache::tags('modmodpacks')->get($cache_key);
        } else {
            $modpack_id_array = [];
            $modpacks_array = [];
            $mod = Mod::where('slug', '=', $name)->first();
            $modpacks = $mod->modpacks()->with('creators')->with('launcher')->with('version')->get();

            foreach ($modpacks as $modpack) {
                if (in_array($modpack->id, $modpack_id_array)) {
                    continue;
                }

                $modpacks_array[] = $this->buildModpackArray($modpack);
                $modpack_id_array[] = $modpack->id;
            }

            Cache::tags('modmodpacks')->forever($cache_key, $modpacks_array);
        }

        return $this->buildDTLauncherOutput($modpacks_array);
    }

    public function getModpackSearch($version)
    {
        $modpack_id_array = [];
        $modpacks_array = [];
        $input = Input::only('mods', 'tags');

        if ($version == 'all') {
            $modpack = Modpack::where('id', '!=', '0'); //there has to be a better way
        } else {
            $version_name = $this->getVersion($version);
            $minecraft_version = MinecraftVersion::where('name', $version_name)->first();
            $modpack = Modpack::where('minecraft_version_id', $minecraft_version->id);
        }

        if ($input['mods']) {
            $input_mod_array = explode(',', $input['mods']);

            foreach ($input_mod_array as $mod) {
                $modpack->whereHas('mods', function ($q) use ($mod) {
                    $q->where('mods.id', '=', $mod);
                });
            }
        }

        if ($input['tags']) {
            $input_tags_array = explode(',', $input['tags']);

            foreach ($input_tags_array as $tag) {
                $modpack->whereHas('tags', function ($q) use ($tag) {
                    $q->where('modpack_tags.id', '=', $tag);
                });
            }
        }

        $modpacks = $modpack->with('creators')->with('launcher')->with('version')->get();

        foreach ($modpacks as $modpack) {
            if (in_array($modpack->id, $modpack_id_array)) {
                continue;
            }

            $modpacks_array[] = $this->buildModpackArray($modpack);
        }

        return $this->buildDTModpackOutput($modpacks_array);
    }

    public function getModpackCompare()
    {
        $mods = [];
        $modpacks = [];

        $input = Input::only('modpacks');
        $modpack_ids = explode(',', $input['modpacks']);

        foreach ($modpack_ids as $id) {
            $modpack = Modpack::find($id);
            $modpack_mods = $modpack->mods;

            $modpacks[$id] = $modpack->name;

            foreach ($modpack_mods as $modpack_mod) {
                $m_id = $modpack_mod->id;
                $mods[$m_id]['name'] = link_to_action('ModController@getMod', $modpack_mod->name, [$modpack_mod->slug]);
                $mods[$m_id]['packs'][] = $modpack->id;
            }
        }

        return $this->buildDTCompareOutput($mods, $modpacks);
    }

    public function getModsSelect($version)
    {
        $version = $this->getVersion($version);
        $sort_array = [];
        $mods_array = [];

        $raw_version = MinecraftVersion::where('name', '=', $version)->first();
        $raw_mods = $raw_version->mods;

        foreach ($raw_mods as $mod) {
            $mod_id = $mod->id;
            $sort_array[$mod_id] = $mod->name;
        }

        natcasesort($sort_array);

        foreach ($sort_array as $k => $mod) {
            $mods_array[] = ['name' => $mod, 'value' => $k];
        }

        return Response::json($mods_array);
    }

    public function getServers()
    {
        $servers_array = [];
        $versions = [];

        $input = Input::only('modpack', 'tags', 'country', 'permission');

        $query = Server::where('active', 1);

        if ($input['modpack']) {
            $input_modpacks_array = explode(',', $input['modpack']);

            $query->where(function ($query) use ($input_modpacks_array) {
                foreach ($input_modpacks_array as $modpack_id) {
                    $query->orWhere('modpack_id', $modpack_id);
                }
            });
        }

        if ($input['tags']) {
            $input_tags_array = explode(',', $input['tags']);

            foreach ($input_tags_array as $tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('server_tags.id', '=', $tag);
                });
            }
        }

        if ($input['country']) {
            $query->where('country', $input['country']);
        }

        if ($input['permission']) {
            $query->where('permissions', $input['permission']);
        }

        $servers = $query->with('modpack')->with('status')->get();

        $countries = Server::countryList();

        $raw_versions = MinecraftVersion::all();

        foreach ($raw_versions as $v) {
            $versions[$v->id] = $v->name;
        }

        foreach ($servers as $server) {
            $server_status = $server->status;
            $modpack = $server->modpack;

            if ($server->server_address_hide == 1) {
                $server_address = 'Hidden / Private';
            } else {
                $server_address = $server->ip_host . ':' . $server->port;
            }

            $version_slug = $this->getVersion($versions[$modpack->minecraft_version_id]);

            $options = '';

            //1 = Whitelist
            //2 = Greylist
            //3 = Open

            switch ($server->permissions) {
                case 1:
                    $options = '<i class="fa fa-lock" title="Whitelist"></i> ';
                    break;
                case 2:
                    $options = '<i class="fa fa-lock" title="Greylist"></i> ';
                    break;
                case 3:
                    $options = '<i class="fa fa-unlock-alt" title="Open"></i> ';
                    break;
            }

            $country_name = $countries[$server->country];

            $options .= '<span class="flag-icon flag-icon-' . strtolower($server->country) . '" title="' . $country_name . '"></span> ';

            $server_name = link_to_action('ServerController@getServer', $server->name, [
                $server->id,
                $server->slug,
            ]);
            $modpack = link_to_action('ModpackController@getModpack', $modpack->name, [
                $version_slug,
                $modpack->slug,
            ]);

            $players = $server_status->current_players . ' / ' . $server_status->max_players;

            $servers_array[] = [
                'id' => $server->id,
                'name' => json_encode($server_name),
                'modpack' => json_encode($modpack),
                'options' => json_encode($options),
                'server_address' => $server_address,
                'players' => $players,
                'deck' => $server->deck,
            ];
        }

        shuffle($servers_array);

        return $this->buildDTServerOutput($servers_array);
    }

    public function getServerPlayers($id)
    {
        $players_array = [];

        $server = Server::find($id);

        if (!$server->player_list_hide) {
            $server_status = ServerStatus::select('id', 'server_id', 'players')->where('server_id', $id)->first();

            $raw_players = json_decode($server_status->players);

            if ($raw_players) {
                foreach ($raw_players as $player) {
                    $players_array[] = [
                        'name' => preg_replace('/\x{00A7}.{1}/u', '', $player->name)
                    ];
                }
            }
        }

        return $this->buildDTServerPlayerOutput($players_array);
    }

    public function getServerMods($id)
    {
        $mods_array = [];

        $server_status = ServerStatus::select('id', 'server_id', 'mods')->where('server_id', $id)->first();

        $raw_mods = json_decode($server_status->mods);

        if ($raw_mods) {
            foreach ($raw_mods->modList as $mod) {

                $mods_array[] = [
                    'name' => $mod->modid,
                    'version' => $mod->version,
                ];
            }
        }

        return $this->buildDTServerModOutput($mods_array);
    }

    public function getTableDataFile($type, $version, $name = null)
    {
        $table_id = 'table-1';
        $table_sdom = '<"top"fp><"clear">t<"bottom"ip><"clear">';
        $table_empty = 'No data available in table.';
        $table_length = 15;
        $table_fixed_header = false;
        $table_order = true;

        switch ($type) {
            case 'mods':
                $columns_array = [
                    'name',
                    'versions',
                    'deck',
                    'authors',
                    'links',
                ];
                $ajax_source = action('JSONController@getTableMods', $version);
                break;

            case 'modpacks':
                $columns_array = [
                    'name',
                    'version',
                    'deck',
                    'creators',
                    'icon_html',
                    'links',
                ];

                $table_empty = 'No Modpacks found.';

                $ajax_source = '/api/table/modpacks/' . $version . '.json';
                break;

            case 'launchers':
                $columns_array = [
                    'name',
                    'version',
                    'deck',
                    'creators',
                    'icon_html',
                    'links',
                ];

                $ajax_source = action('JSONController@getTableLaunchers', [
                    $name,
                    $version,
                ]);
                break;

            case 'modpackmods':
                $columns_array = [
                    'name',
                    'versions',
                    'deck',
                    'authors',
                    'links',
                ];

                $ajax_source = action('JSONController@getTableModpackMods', [$name]);
                break;

            case 'modmodpacks':
                $columns_array = [
                    'name',
                    'version',
                    'deck',
                    'creators',
                    'icon_html',
                    'links',
                ];

                $ajax_source = action('JSONController@getModModpacks', [$name]);
                break;

            case 'compare':
                $table_length = 1000;
                $table_fixed_header = true;

                $input = Input::only('modpacks');

                $modpack_ids = explode(',', $input['modpacks']);

                $columns_array = ['name'];

                foreach ($modpack_ids as $id) {
                    $modpack = Modpack::find($id);
                    $columns_array[] = $modpack->id;
                }

                $ajax_source = action('JSONController@getModpackCompare') . '?modpacks=' . $input['modpacks'];
                break;

            case 'modpackfinder':
                $input = Input::only('mods', 'tags');
                $columns_array = [
                    'name',
                    'version',
                    'deck',
                    'creators',
                    'icon_html',
                    'links',
                ];

                $table_empty = 'No Modpacks found.';

                if ($input['tags'] && $input['mods']) {
                    $ajax_source = action('JSONController@getModpackSearch',
                            [$version]) . '?mods=' . $input['mods'] . '&tags=' . $input['tags'];
                } elseif ($input['mods']) {
                    $ajax_source = action('JSONController@getModpackSearch', [$version]) . '?mods=' . $input['mods'];
                } elseif ($input['tags']) {
                    $ajax_source = action('JSONController@getModpackSearch', [$version]) . '?tags=' . $input['tags'];
                } else {
                    $ajax_source = action('JSONController@getModpackSearch', [$version]);
                }
                break;

            case 'servers':
                $table_id = 'servers-table';
                $table_order = false;
                $table_empty = 'No servers found.';
                $query_array = [];
                $query_string = '';

                $input = Input::only('modpack', 'tags', 'country', 'permission');
                $columns_array = [
                    'options',
                    'name',
                    'modpack',
                    'server_address',
                    'players',
                    'deck',
                ];

                if ($input['modpack']) {
                    $query_array[] = 'modpack=' . $input['modpack'];
                }

                if ($input['tags']) {
                    $tag_string = 'tags=';
                    $exploded_tags = explode(',', strtolower($input['tags']));

                    foreach ($exploded_tags as $t) {
                        $tag_string .= $t . ',';
                    }
                    $query_array[] = rtrim($tag_string, ',');
                }

                if ($input['country']) {
                    $query_array[] = 'country=' . $input['country'];
                }

                if ($input['permission']) {
                    $query_array[] = 'permission=' . $input['permission'];
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

                $ajax_source = action('JSONController@getServers') . $query_string;

                break;

            case 'serverplayers':
                $columns_array = [
                    'name',
                ];

                $table_id = 'server-players';
                $table_sdom = '<"top"p><"clear">t<"bottom"ip><"clear">';
                $table_empty = 'Player list is private or no players are present.';

                $input = Input::only('id');

                $ajax_source = action('JSONController@getServerPlayers', [$input['id']]);

                break;

            case 'servermods':
                $columns_array = [
                    'mod',
                    'version',
                ];

                $table_id = 'server-mods';
                $table_sdom = '<"top"p><"clear">t<"bottom"ip><"clear">';
                $table_empty = 'No mods returned from server.';

                $input = Input::only('id');

                $ajax_source = action('JSONController@getServerMods', [$input['id']]);

                break;
        }

        return Response::view('api.table.data', [
            'type' => $type,
            'ajax_source' => $ajax_source,
            'columns' => $columns_array,
            'table_id' => $table_id,
            'table_sdom' => $table_sdom,
            'table_empty' => $table_empty,
            'table_length' => $table_length,
            'table_fixed_header' => $table_fixed_header,
            'table_order' => $table_order,
        ], 200, ['Content-Type' => 'application/json']);
    }
}
