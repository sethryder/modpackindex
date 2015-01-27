<?php

class SearchController extends BaseController
{
    public function getModpackSearch($version)
    {
        $url_version = $version;
        $version = preg_replace('/-/', '.', $version);

        $input = Input::only('tag');

        $title = $version . ' Modpack Finder - '. $this->site_name;

        $tag_select_array = [];
        $mod_select_array = [];

        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();
        $mods = $minecraft_version->mods;
        foreach ($mods as $mod)
        {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        $tags = ModpackTag::all();
        foreach ($tags as $tag)
        {
            $id = $tag->id;
            $tag_select_array[$id] = $tag->name;
        }

        asort($mod_select_array);
        asort($tag_select_array);

        if ($input['tag'])
        {
            $tag = ModpackTag::where('slug', $input['tag'])->first();

            if (!$tag)
            {
                return Redirect::to('/modpack/finder/'.$url_version);
            }

            $tag_id = $tag->id;

            $selected_tags = ["$tag_id"];
            $selected_mods = [];

            $table_javascript = '/api/table/modpackfinder_'. $url_version .'.json?tags='. $tag->id;

            return View::make('search.modpack', ['chosen' => true, 'mods' => $mod_select_array, 'tags' => $tag_select_array,
                'version' => $version, 'url_version' => $url_version, 'title' => $title, 'results' => true,
                'table_javascript' => $table_javascript, 'selected_tags' => $selected_tags, 'selected_mods' => $selected_mods]);

        }
        else
        {
            return View::make('search.modpack', ['chosen' => true, 'mods' => $mod_select_array, 'tags' => $tag_select_array,
                'version' => $version, 'url_version' => $url_version, 'title' => $title]);
        }
    }

    public function postModpackSearch($version)
    {
        $url_version = $version;
        $version = preg_replace('/-/', '.', $version);
        $input = Input::only('mods', 'tags');

        if (!$input['mods'] && !$input['tags'])
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

        $tags = ModpackTag::all();
        foreach ($tags as $tag)
        {
            $id = $tag->id;
            $tag_select_array[$id] = $tag->name;
        }

        asort($mod_select_array);
        asort($tag_select_array);

        $mods_string = '';
        $tags_string = '';

        if ($input['mods'])
        {
            foreach ($input['mods'] as $mod)
            {
                $mods_string .= $mod . ',';
            }

            $mods_string = rtrim($mods_string, ',');
        }

        if ($input['tags'])
        {
            foreach ($input['tags'] as $tag)
            {
                $tags_string .= $tag . ',';
            }

            $tags_string = rtrim($tags_string, ',');
        }


        if ($input['mods'] && $input['tags'])
        {
            $table_javascript = '/api/table/modpackfinder_'. $url_version .'.json?mods='. $mods_string . '&tags=' . $tags_string;
        }
        elseif ($input['mods'])
        {
            $table_javascript = '/api/table/modpackfinder_'. $url_version .'.json?mods='. $mods_string;
        }
        elseif ($input['tags'])
        {
            $table_javascript = '/api/table/modpackfinder_'. $url_version .'.json?tags='. $tags_string;
        }

        return View::make('search.modpack', ['chosen' => true, 'version' => $version, 'url_version' => $url_version,
            'results' => true, 'table_javascript' => $table_javascript, 'mods' => $mod_select_array, 'tags' => $tag_select_array,
            'selected_mods' => $input['mods'], 'selected_tags' => $input['tags']]);
    }
}