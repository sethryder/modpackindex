<?php

class JSONController extends BaseController
{
    public function getTableMods($version = 'all')
    {
        $cache_key = 'table-mods-' . $version;

        if (Cache::tags('mods')->has($cache_key)) {
            $mods_array = Cache::tags('mods')->get($cache_key);
        } else {
            $mods_array = [];
            $mod_id_array = [];
            $version = preg_replace('/-/', '.', $version);

            if ($version == 'all') {
                $raw_mods = Mod::with('versions')->with('authors')->get();
            } else {
                $raw_version = MinecraftVersion::where('name', '=', $version)->with('mods')->first();
                $raw_mods = $raw_version->mods()->with('authors')->with('versions')->get();
            }

            foreach ($raw_mods as $mod) {
                $supported_versions = '';
                $authors = '';
                $links = '';
                $version_array = [];
                $i = 0;

                if ($mod->mod_list_hide == 1) {
                    continue;
                }

                if (in_array($mod->id, $mod_id_array)) {
                    continue;
                }

                $name = link_to_route('ModController@getMod', $mod->name, [$mod->slug]);

                foreach ($mod->versions as $v) {
                    if (!in_array($v->name, $version_array)) {
                        $version_array[] = $v->name;
                        $supported_versions .= $v->name;
                        $supported_versions .= ', ';
                    }
                    ++$i;
                }

                if (!$supported_versions) {
                    $supported_versions = 'Unknown';
                }

                foreach ($mod->authors as $v) {
                    $authors .= $v->name;
                    $authors .= ', ';
                }

                if (!$authors) {
                    $authors = 'N/A';
                }

                if ($mod->website) {
                    $links .= link_to($mod->website, 'Website');
                    $links .= ' / ';
                }

                if ($mod->donate_link) {
                    $links .= link_to($mod->donate_link, 'Donate');
                    $links .= ' / ';
                }

                if ($mod->wiki_link) {
                    $links .= link_to($mod->wiki_link, 'Wiki');
                    $links .= ' / ';
                }

                $mods_array[] = [
                    'name' => $name,
                    'deck' => json_encode($mod->deck),
                    'links' => json_encode(rtrim($links, ' / ')),
                    'versions' => rtrim($supported_versions, ', '),
                    'authors' => rtrim($authors, ', '),
                ];

                $mod_id_array[] = $mod->id;
            }

            Cache::tags('mods')->forever($cache_key, $mods_array);
        }

        return View::make('api.table.mods.json', [
                'mods' => $mods_array,
                'version' => $version,
            ]);
    }

    public function getTableModpacks($get_version = 'all')
    {
        $cache_key = 'table-modpacks-' . $get_version;

        if (Cache::tags('modpacks')->has($cache_key)) {
            $modpacks_array = Cache::tags('modpacks')->get($cache_key);
        } else {
            $modpacks_array = [];
            $modpack_id_array = [];
            $get_version = preg_replace('/-/', '.', $get_version);

            if ($get_version == 'all') {
                $raw_modpacks = Modpack::with('creators')->with('version')->with('launcher')->get();
            } else {
                $raw_version = MinecraftVersion::where('name', '=', $get_version)->with('modpacks')->first();
                $raw_modpacks = $raw_version->modpacks()->with('creators')->with('version')->with('launcher')->get();
            }

            foreach ($raw_modpacks as $modpack) {
                $creators = '';
                $links = '';

                if (in_array($modpack->id, $modpack_id_array)) {
                    continue;
                }

                $name = link_to_action('ModpackController@getModpack', $modpack->name, [
                        preg_replace('/\./', '-', $modpack->version->name),
                        $modpack->slug,
                    ]);

                switch ($modpack->launcher->short_name) {
                    case 'ftb':
                        $icon = asset('/static/img/icons/ftb.png');
                        break;
                    case 'atlauncher':
                        $icon = asset('/static/img/icons/atlauncher.png');
                        break;
                    case 'technic':
                        $icon = asset('/static/img/icons/technic.png');
                        break;
                    case 'curse':
                        $icon = asset('/static/img/icons/curse.png');
                        break;
                    default:
                        $icon = asset('/static/img/icons/custom.png');
                }

                $icon_html = [
                    'icon' => $icon,
                    'link' => action('LauncherController@getLauncherVersion', [$modpack->launcher->slug]),
                    'title' => $modpack->launcher->short_name ? $modpack->launcher->short_name : 'custom',
                    'launcher_id' => $modpack->launcher->id,
                ];

                foreach ($modpack->creators as $v) {
                    $creators .= $v->name;
                    $creators .= ', ';
                }

                if (!$creators) {
                    $creators = 'N/A';
                }

                if ($get_version == 'all') {
                    $version = $modpack->version->name;
                } else {
                    $version = $raw_version->name;
                }

                if ($modpack->website) {
                    $links .= '<a href="' . $modpack->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($modpack->donate_link) {
                    $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($modpack->wiki_link) {
                    $links .= '<a href="' . $modpack->wiki_link . '">Wiki</a>';
                    $links .= ' / ';
                }

                $modpacks_array[] = [
                    'icon_html' => json_encode($icon_html),
                    'name' => $name,
                    'icon' => $icon,
                    'deck' => json_encode($modpack->deck),
                    'links' => json_encode(rtrim($links, ' / ')),
                    'version' => $version,
                    'creators' => rtrim($creators, ', '),
                ];

                $mod_id_array[] = $modpack->id;
            }

            Cache::tags('modpacks')->forever($cache_key, $modpacks_array);
        }

        return View::make('api.table.modpacks.json', ['modpacks' => $modpacks_array]);
    }

    public function getTableLaunchers($name, $get_version = 'all')
    {
        $cache_key = 'table-launchers-' . $name . '-' . $get_version;

        if (Cache::tags('launchers')->has($cache_key)) {
            $modpacks_array = Cache::tags('launchers')->get($cache_key);
        } else {
            $modpacks_array = [];
            $modpack_id_array = [];
            $get_version = preg_replace('/-/', '.', $get_version);
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
                $creators = '';
                $links = '';

                if (in_array($modpack->id, $modpack_id_array)) {
                    continue;
                }

                $name = link_to_action('ModpackController@getModpack', $modpack->name, [
                        preg_replace('/\./', '-', $modpack->version->name),
                        $modpack->slug,
                    ]);

                switch ($modpack->launcher->short_name) {
                    case 'ftb':
                        $icon = '/static/img/icons/ftb.png';
                        break;
                    case 'atlauncher':
                        $icon = '/static/img/icons/atlauncher.png';
                        break;
                    case 'technic':
                        $icon = '/static/img/icons/technic.png';
                        break;
                    case 'curse':
                        $icon = '/static/img/icons/curse.png';
                        break;
                    default:
                        $icon = '/static/img/icons/custom.png';
                }

                $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

                foreach ($modpack->creators as $v) {
                    $creators .= $v->name;
                    $creators .= ', ';
                }

                if (!$creators) {
                    $creators = 'N/A';
                }

                if ($get_version == 'all') {
                    $version = $modpack->version->name;
                } else {
                    $version = $get_version;
                }

                if ($modpack->website) {
                    $links .= '<a href="' . $modpack->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($modpack->donate_link) {
                    $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($modpack->wiki_link) {
                    $links .= '<a href="' . $modpack->wiki_link . '">Wiki</a>';
                    $links .= ' / ';
                }

                $modpacks_array[] = [
                    'icon_html' => json_encode($icon_html),
                    'name' => $name,
                    'icon' => $icon,
                    'deck' => json_encode($modpack->deck),
                    'links' => json_encode(rtrim($links, ' / ')),
                    'version' => $version,
                    'creators' => rtrim($creators, ', '),
                ];

                $mod_id_array[] = $modpack->id;
            }

            Cache::tags('launchers')->forever($cache_key, $modpacks_array);
        }

        return View::make('api.table.launchers.json', ['modpacks' => $modpacks_array]);
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
                $supported_versions = '';
                $authors = '';
                $links = '';
                $version_array = [];
                $i = 0;

                if (in_array($mod->id, $mod_id_array)) {
                    continue;
                }

                $name = '<a href=/mod/' . $mod->slug . '>' . $mod->name . '</a>';

                foreach ($mod->versions as $v) {
                    if (!in_array($v->name, $version_array)) {
                        $version_array[] = $v->name;
                        $supported_versions .= $v->name;
                        $supported_versions .= ', ';
                    }
                    ++$i;
                }

                if (!$supported_versions) {
                    $supported_versions = 'Unknown';
                }

                foreach ($mod->authors as $v) {
                    $authors .= $v->name;
                    $authors .= ', ';
                }

                if (!$authors) {
                    $authors = 'N/A';
                }

                if ($mod->website) {
                    $links .= '<a href="' . $mod->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($mod->donate_link) {
                    $links .= '<a href="' . $mod->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($mod->wiki_link) {
                    $links .= '<a href="' . $mod->wiki_link . '">Wiki</a>';
                    $links .= ' / ';
                }

                $mods_array[] = [
                    'name' => $name,
                    'deck' => json_encode($mod->deck),
                    'links' => json_encode(rtrim($links, ' / ')),
                    'versions' => rtrim($supported_versions, ', '),
                    'authors' => rtrim($authors, ', '),
                ];

                $mod_id_array[] = $mod->id;
            }

            Cache::tags('modpackmods')->forever($cache_key, $mods_array);
        }

        return View::make('api.table.mods.json', [
                'mods' => $mods_array,
                'version' => null,
            ]);
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
                $creators = '';
                $links = '';

                if (in_array($modpack->id, $modpack_id_array)) {
                    continue;
                }

                $name = '<a href=/modpack/' . preg_replace('/\./', '-',
                        $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';

                switch ($modpack->launcher->short_name) {
                    case 'ftb':
                        $icon = '/static/img/icons/ftb.png';
                        break;
                    case 'atlauncher':
                        $icon = '/static/img/icons/atlauncher.png';
                        break;
                    case 'technic':
                        $icon = '/static/img/icons/technic.png';
                        break;
                    case 'curse':
                        $icon = '/static/img/icons/curse.png';
                        break;
                    default:
                        $icon = '/static/img/icons/custom.png';
                }

                $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

                foreach ($modpack->creators as $v) {
                    $creators .= $v->name;
                    $creators .= ', ';
                }

                if (!$creators) {
                    $creators = 'N/A';
                }

                $version = $modpack->version->name;

                if ($modpack->website) {
                    $links .= '<a href="' . $modpack->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($modpack->donate_link) {
                    $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($modpack->wiki_link) {
                    $links .= '<a href="' . $modpack->wiki_link . '">Wiki</a>';
                    $links .= ' / ';
                }

                $modpacks_array[] = [
                    'icon_html' => json_encode($icon_html),
                    'name' => $name,
                    'icon' => $icon,
                    'deck' => json_encode($modpack->deck),
                    'links' => json_encode(rtrim($links, ' / ')),
                    'version' => $version,
                    'creators' => rtrim($creators, ', '),
                ];

                $modpack_id_array[] = $modpack->id;
            }

            Cache::tags('modmodpacks')->forever($cache_key, $modpacks_array);
        }

        return View::make('api.table.launchers.json', ['modpacks' => $modpacks_array]);
    }

    public function getModpackSearch($version)
    {
        $modpack_id_array = [];
        $modpacks_array = [];
        $input = Input::only('mods', 'tags');

        if ($version == 'all') {
            $modpack = Modpack::where('id', '!=', '0'); //there has to be a better way
        } else {
            $version_name = preg_replace('/-/', '.', $version);
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
            $creators = '';
            $links = '';

            if (in_array($modpack->id, $modpack_id_array)) {
                continue;
            }

            $name = '<a href=/modpack/' . preg_replace('/\./', '-',
                    $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';

            switch ($modpack->launcher->short_name) {
                case 'ftb':
                    $icon = '/static/img/icons/ftb.png';
                    break;
                case 'atlauncher':
                    $icon = '/static/img/icons/atlauncher.png';
                    break;
                case 'technic':
                    $icon = '/static/img/icons/technic.png';
                    break;
                case 'curse':
                    $icon = '/static/img/icons/curse.png';
                    break;
                default:
                    $icon = '/static/img/icons/custom.png';
            }

            $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

            foreach ($modpack->creators as $v) {
                $creators .= $v->name;
                $creators .= ', ';
            }

            if (!$creators) {
                $creators = 'N/A';
            }

            $version = $modpack->version->name;

            if ($modpack->website) {
                $links .= '<a href="' . $modpack->website . '">Website</a>';
                $links .= ' / ';
            }

            if ($modpack->donate_link) {
                $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                $links .= ' / ';
            }

            if ($modpack->wiki_link) {
                $links .= '<a href="' . $modpack->wiki_link . '">Wiki</a>';
                $links .= ' / ';
            }

            $modpacks_array[] = [
                'icon_html' => json_encode($icon_html),
                'name' => $name,
                'icon' => $icon,
                'deck' => json_encode($modpack->deck),
                'links' => json_encode(rtrim($links, ' / ')),
                'version' => $version,
                'creators' => rtrim($creators, ', '),
            ];
        }

        return View::make('api.table.modpacks.json', ['modpacks' => $modpacks_array]);
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

            foreach ($modpack_mods as $m) {
                $m_id = $m->id;
                $mods[$m_id]['name'] = '<a href=/mod/' . $m->slug . '>' . $m->name . '</a>';
                $mods[$m_id]['packs'][] = $modpack->id;
            }
        }

        return View::make('api.table.modpacks.compare', [
                'mods' => $mods,
                'modpacks' => $modpacks,
            ]);
    }

    public function getModsSelect($version)
    {
        $version = preg_replace('/-/', '.', $version);
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
            $mods_array[] = [
                'name' => $mod,
                'value' => $k,
            ];
        }

        return json_encode($mods_array);
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

            $version_slug = preg_replace('/\./', '-', $versions[$modpack->minecraft_version_id]);

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

        return View::make('api.table.servers.json', ['servers' => $servers_array]);
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
                        'name' => preg_replace('/\x{00A7}.{1}/u', '', $player->name),
                    ];
                }
            }
        }

        return View::make('api.table.servers.players', ['players' => $players_array]);
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

        return View::make('api.table.servers.mods', ['mods' => $mods_array]);
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
                $ajax_source = action('JSONController@getTableModpacks', $version);
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

                $ajax_source = action('JSONController@getTableModpackMods', $name);
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

                $ajax_source = action('JSONController@getModModpacks', $name);
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
                            $version) . '?mods=' . $input['mods'] . '&tags=' . $input['tags'];
                } elseif ($input['mods']) {
                    $ajax_source = action('JSONController@getModpackSearch', $version) . '?mods=' . $input['mods'];
                } elseif ($input['tags']) {
                    $ajax_source = action('JSONController@getModpackSearch', $version) . '?tags=' . $input['tags'];
                } else {
                    $ajax_source = action('JSONController@getModpackSearch', $version);
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

                    ++$query_count;
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

                $ajax_source = action('JSONController@getServerPlayers', $input['id']);
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

                $ajax_source = action('JSONController@getServerMods', $input['id']);

                break;
        }

        return View::make('api.table.data', [
                'ajax_source' => $ajax_source,
                'columns' => $columns_array,
                'table_id' => $table_id,
                'table_sdom' => $table_sdom,
                'table_empty' => $table_empty,
                'table_length' => $table_length,
                'table_fixed_header' => $table_fixed_header,
                'table_order' => $table_order,
            ]);
    }
}
