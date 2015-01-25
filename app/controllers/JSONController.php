<?php

class JSONController extends BaseController
{
    public function getTableMods($version = 'all')
    {
        $cache_key = 'table-mods-' . $version;

        if (Cache::tags('mods')->has($cache_key))
        {
            $mods_array = Cache::tags('mods')->get($cache_key);
        }
        else
        {
            $mods_array = [];
            $mod_id_array = [];
            $version = preg_replace('/-/', '.', $version);

            if ($version == 'all')
            {
                $raw_mods = Mod::all();
            }
            else
            {
                $raw_version = MinecraftVersion::where('name', '=', $version)->first();
                $raw_mods = $raw_version->mods;
            }

            foreach ($raw_mods as $mod)
            {
                $supported_versions = '';
                $authors = '';
                $links = '';
                $version_array = [];
                $i = 0;

                if ($mod->mod_list_hide == 1)
                {
                    continue;
                }

                if (in_array($mod->id, $mod_id_array))
                {
                    continue;
                }

                $name = '<a href=/mod/' . $mod->slug . '>' . $mod->name . '</a>';

                foreach ($mod->versions as $v)
                {
                    if (!in_array($v->name, $version_array))
                    {
                        $version_array[] = $v->name;
                        $supported_versions .= $v->name;
                        $supported_versions .= ', ';
                    }
                    $i++;
                }

                if (!$supported_versions) $supported_versions = 'Unknown';

                foreach ($mod->authors as $v)
                {
                    $authors .= $v->name;
                    $authors .= ', ';
                }

                if (!$authors) $authors = 'N/A';

                if ($mod->website)
                {
                    $links .= '<a href="' . $mod->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($mod->donate_link)
                {
                    $links .= '<a href="' . $mod->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($mod->wiki_link)
                {
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

            Cache::tags('mods')->forever($cache_key, $mods_array);
        }

        return View::make('api.table.mods.json', ['mods' => $mods_array, 'version' => $version]);
    }

    public function getTableModpacks($get_version = 'all')
    {
        $cache_key = 'table-modpacks-' . $get_version;

        if (Cache::tags('modpacks')->has($cache_key))
        {
            $modpacks_array = Cache::tags('modpacks')->get($cache_key);
        }
        else
        {
            $modpacks_array = [];
            $modpack_id_array = [];
            $get_version = preg_replace('/-/', '.', $get_version);

            if ($get_version == 'all')
            {
                $raw_modpacks = Modpack::all();
            }
            else
            {
                $raw_version = MinecraftVersion::where('name', '=', $get_version)->first();
                $raw_modpacks = $raw_version->modpacks;
            }

            foreach ($raw_modpacks as $modpack)
            {
                $creators = '';
                $links = '';

                if (in_array($modpack->id, $modpack_id_array))
                {
                    continue;
                }

                $name = '<a href=/modpack/' . preg_replace('/\./', '-', $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';

                switch ($modpack->launcher->short_name)
                {
                    case 'ftb':
                        $icon = '/static/img/icons/ftb.png';
                        break;
                    case 'atlauncher':
                        $icon = '/static/img/icons/atlauncher.png';
                        break;
                    case 'technic':
                        $icon = '/static/img/icons/technic.png';
                        break;
                    default:
                        $icon = '/static/img/icons/custom.png';
                }

                $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

                foreach ($modpack->creators as $v)
                {
                    $creators .= $v->name;
                    $creators .= ', ';
                }

                if (!$creators) $creators = 'N/A';

                if ($get_version == 'all')
                {
                    $version = $modpack->version->name;
                }
                else
                {
                    $version = $raw_version->name;
                }

                if ($modpack->website)
                {
                    $links .= '<a href="' . $modpack->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($modpack->donate_link)
                {
                    $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($modpack->wiki_link)
                {
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
        $cache_key = 'table-launchers-' . $name .'-' . $get_version;

        if (Cache::tags('launchers')->has($cache_key))
        {
            $modpacks_array = Cache::tags('launchers')->get($cache_key);
        }
        else
        {
            $modpacks_array = [];
            $modpack_id_array = [];
            $get_version = preg_replace('/-/', '.', $get_version);
            $launcher = Launcher::where('slug', '=', $name)->first();
            $launcher_id = $launcher->id;

            if ($get_version == 'all')
            {
                $raw_modpacks = Modpack::where('launcher_id', '=', $launcher_id)->get();
            }
            else
            {
                $version = MinecraftVersion::where('name', '=', $get_version)->first();
                $version_id = $version->id;

                $raw_modpacks = Modpack::where('launcher_id', '=', $launcher_id)
                    ->where('minecraft_version_id', '=', $version_id)->get();
            }

            foreach ($raw_modpacks as $modpack)
            {
                $creators = '';
                $links = '';

                if (in_array($modpack->id, $modpack_id_array))
                {
                    continue;
                }

                $name = '<a href=/modpack/' . preg_replace('/\./', '-', $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';

                switch ($modpack->launcher->short_name)
                {
                    case 'ftb':
                        $icon = '/static/img/icons/ftb.png';
                        break;
                    case 'atlauncher':
                        $icon = '/static/img/icons/atlauncher.png';
                        break;
                    case 'technic':
                        $icon = '/static/img/icons/technic.png';
                        break;
                    default:
                        $icon = '/static/img/icons/custom.png';
                }

                $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

                foreach ($modpack->creators as $v)
                {
                    $creators .= $v->name;
                    $creators .= ', ';
                }

                if (!$creators) $creators = 'N/A';

                if ($get_version == 'all')
                {
                    $version = $modpack->version->name;
                }
                else
                {
                    $version = $get_version;
                }

                if ($modpack->website)
                {
                    $links .= '<a href="' . $modpack->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($modpack->donate_link)
                {
                    $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($modpack->wiki_link)
                {
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

        if (Cache::tags('modpackmods')->has($cache_key))
        {
            $mods_array = Cache::tags('modpackmods')->get($cache_key);
        }
        else
        {
            $mods_array = [];
            $mod_id_array = [];
            $modpack = Modpack::where('slug', '=', $name)->first();
            $raw_mods = $modpack->mods;

            //print_r($raw_mods);

            foreach ($raw_mods as $mod)
            {
                $supported_versions = '';
                $authors = '';
                $links = '';
                $version_array = [];
                $i = 0;

                if (in_array($mod->id, $mod_id_array))
                {
                    continue;
                }

                $name = '<a href=/mod/' . $mod->slug . '>' . $mod->name . '</a>';

                foreach ($mod->versions as $v)
                {
                    if (!in_array($v->name, $version_array))
                    {
                        $version_array[] = $v->name;
                        $supported_versions .= $v->name;
                        $supported_versions .= ', ';
                    }
                    $i++;
                }

                if (!$supported_versions) $supported_versions = 'Unknown';

                foreach ($mod->authors as $v)
                {
                    $authors .= $v->name;
                    $authors .= ', ';
                }

                if (!$authors) $authors = 'N/A';

                if ($mod->website)
                {
                    $links .= '<a href="' . $mod->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($mod->donate_link)
                {
                    $links .= '<a href="' . $mod->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($mod->wiki_link)
                {
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

        return View::make('api.table.mods.json', ['mods' => $mods_array, 'version' => null]);
    }

    public function getModModpacks($name)
    {
        $cache_key = 'table-modmodpacks-' . $name;

        if (Cache::tags('modmodpacks')->has($cache_key))
        {
            $modpacks_array = Cache::tags('modmodpacks')->get($cache_key);
        }
        else
        {
            $modpack_id_array = [];
            $modpacks_array = [];
            $mod = Mod::where('slug', '=', $name)->first();
            $modpacks = $mod->modpacks;

            foreach ($modpacks as $modpack)
            {
                $creators = '';
                $links = '';

                if (in_array($modpack->id, $modpack_id_array))
                {
                    continue;
                }

                $name = '<a href=/modpack/' . preg_replace('/\./', '-', $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';

                switch ($modpack->launcher->short_name)
                {
                    case 'ftb':
                        $icon = '/static/img/icons/ftb.png';
                        break;
                    case 'atlauncher':
                        $icon = '/static/img/icons/atlauncher.png';
                        break;
                    case 'technic':
                        $icon = '/static/img/icons/technic.png';
                        break;
                    default:
                        $icon = '/static/img/icons/custom.png';
                }

                $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

                foreach ($modpack->creators as $v)
                {
                    $creators .= $v->name;
                    $creators .= ', ';
                }

                if (!$creators) $creators = 'N/A';

                $version = $modpack->version->name;

                if ($modpack->website)
                {
                    $links .= '<a href="' . $modpack->website . '">Website</a>';
                    $links .= ' / ';
                }

                if ($modpack->donate_link)
                {
                    $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                    $links .= ' / ';
                }

                if ($modpack->wiki_link)
                {
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
        $input = Input::only('mods');

        $input_mod_array = explode(',', $input['mods']);

        $version = preg_replace('/-/', '.', $version);
        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();

        $modpack = Modpack::where('minecraft_version_id', '=', $minecraft_version->id);

        foreach ($input_mod_array as $mod)
        {
            $modpack->whereHas('mods', function ($q) use ($mod)
            {
                $q->where('mods.id', '=', $mod);
            });
        }

        $modpacks = $modpack->get();

        foreach ($modpacks as $modpack)
        {
            $creators = '';
            $links = '';

            if (in_array($modpack->id, $modpack_id_array))
            {
                continue;
            }

            $name = '<a href=/modpack/' . preg_replace('/\./', '-', $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';

            switch ($modpack->launcher->short_name)
            {
                case 'ftb':
                    $icon = '/static/img/icons/ftb.png';
                    break;
                case 'atlauncher':
                    $icon = '/static/img/icons/atlauncher.png';
                    break;
                case 'technic':
                    $icon = '/static/img/icons/technic.png';
                    break;
                default:
                    $icon = '/static/img/icons/custom.png';
            }

            $icon_html = '<a href="/launcher/' . $modpack->launcher->slug . '"><img src="' . $icon . '"/></a>';

            foreach ($modpack->creators as $v)
            {
                $creators .= $v->name;
                $creators .= ', ';
            }

            if (!$creators) $creators = 'N/A';

            $version = $modpack->version->name;

            if ($modpack->website)
            {
                $links .= '<a href="' . $modpack->website . '">Website</a>';
                $links .= ' / ';
            }

            if ($modpack->donate_link)
            {
                $links .= '<a href="' . $modpack->donate_link . '">Donate</a>';
                $links .= ' / ';
            }

            if ($modpack->wiki_link)
            {
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

        return View::make('api.table.modpacks.json', ['modpacks' => $modpacks_array, 'version' => $version]);
    }

    public function getTableDataFile($type, $version, $name = null)
    {
        switch ($type)
        {
            case 'mods':

                $columns_array = [
                    'name',
                    'versions',
                    'deck',
                    'authors',
                    'links',
                ];
                $ajax_source = '/api/table/mods/' . $version . '.json';
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

                $ajax_source = '/api/table/launchers/' . $name . '/' . $version . '.json';
                break;
            case 'modpackmods':
                $columns_array = [
                    'name',
                    'versions',
                    'deck',
                    'authors',
                    'links',
                ];

                $ajax_source = '/api/table/modpack/mods/' . $name . '.json';
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

                $ajax_source = '/api/table/mod/modpacks/' . $name . '.json';
                break;

            case 'modpackfinder':
                $input = Input::only('mods');
                $columns_array = [
                    'name',
                    'version',
                    'deck',
                    'creators',
                    'icon_html',
                    'links',
                ];
                $ajax_source = '/api/table/modpackfinder/' . $version . '.json?mods=' . $input['mods'];
                break;

        }
        return View::make('api.table.data', ['ajax_source' => $ajax_source, 'columns' => $columns_array]);
    }
}