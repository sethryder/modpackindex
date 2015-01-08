<?php

class SearchController extends BaseController
{
    public function getModpackSearch($version)
    {
        $url_version = $version;
        $version = preg_replace('/-/', '.', $version);

        $mod_select_array = [];

        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();
        $mods = $minecraft_version->mods;
        foreach ($mods as $mod)
        {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        asort($mod_select_array);

        return View::make('search.modpack', ['chosen' => true, 'mods' => $mod_select_array, 'version' => $version,
            'url_version' => $url_version]);
    }

    public function postModpackSearch($version)
    {
        $url_version = $version;
        $version = preg_replace('/-/', '.', $version);
        $input = Input::only('mods');

        if (!$input['mods'])
        {
            return Redirect::to('/modpack/finder/'.$url_version);
        }

        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();
        $mods = $minecraft_version->mods;
        foreach ($mods as $mod)
        {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        asort($mod_select_array);

        $mods_string = '';
        foreach ($input['mods'] as $mod)
        {
            $mods_string .= $mod . ',';
        }

        $mods_string = rtrim($mods_string, ',');

        $table_javascript = '/api/table/modpackfinder_'. $url_version .'.json?mods='. $mods_string;

        return View::make('search.modpack', ['chosen' => true, 'version' => $version, 'url_version' => $url_version,
            'results' => true, 'table_javascript' => $table_javascript, 'mods' => $mod_select_array,
            'selected_mods' => $input['mods']]);
    }
}