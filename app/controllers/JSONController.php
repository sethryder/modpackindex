<?php

class JSONController extends BaseController
{
    public function getTableMods($version)
    {
        $mods_array = [];
        $mod_id_array = [];
        $version = preg_replace('/-/', '.', $version);

        $raw_version = MinecraftVersion::where('name', '=', $version)->first();
        $raw_mods = $raw_version->mods;

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

            foreach ($mod->authors as $v)
            {
                $authors .= $v->name;
                $authors .= ', ';
            }

            if ($mod->website)
            {
                $links .= '<a href="' . $mod->website . '">Website</a>';
                $links .= ' | ';
            }

            if ($mod->donate_link)
            {
                $links .= '<a href="' . $mod->donate_link . '">Donate</a>';
                $links .= ' | ';
            }

            if ($mod->wiki_link)
            {
                $links .= '<a href="' . $mod->wiki_link . '">Wiki</a>';
                $links .= ' | ';
            }

            $mods_array[] = [
                'name' => $mod->name,
                'deck' => $mod->deck,
                'links' => json_encode(rtrim($links, ' | ')),
                'versions' => rtrim($supported_versions, ', '),
                'authors' => rtrim($authors, ', '),
            ];

            $mod_id_array[] = $mod->id;
        }

        return View::make('api.table.mods.json', ['mods' => $mods_array]);
    }

    public function getTableDataFile($type, $version)
    {
        if ($type == 'mods')
        {
            $columns_array = [
                'name',
                'versions',
                'deck',
                'authors',
                'links',
            ];

            $ajax_source = '/api/table/mods/'. $version. '.json';
        }

        return View::make('api.table.data', ['ajax_source' => $ajax_source, 'columns' => $columns_array]);

    }

    public function getTableModpacks($version)
    {
        $version = preg_replace('/-/', '.', $version);
        echo $version;
    }
}