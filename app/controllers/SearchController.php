<?php

class SearchController extends BaseController
{
    public function getModpackSearch()
    {
        $input = Input::only('tag');

        $title = ' Modpack Finder - '. $this->site_name;

        if ($input['tag'])
        {
            $tag = ModpackTag::where('slug', $input['tag'])->first();

            if (!$tag)
            {
                return Redirect::to('/modpack/finder/');
            }

            $tag_id = $tag->id;

            $title = $tag->name . ' Modpacks - Pack Finder - ' . $this->site_name;
            $meta_description = 'Find and discover' . $tag->name . 'Modpacks. Refine your search for packs that include specific mods.';

            $selected_tags = ["$tag_id"];
            $selected_mods = [];

            $table_javascript = '/api/table/modpackfinder_all.json?tags='. $tag->id;

            return View::make('search.modpack', ['chosen' => true, 'mods' => [], 'title' => $title, 'results' => true,
                'table_javascript' => $table_javascript, 'selected_tags' => $selected_tags, 'selected_mods' => $selected_mods,
                'mc_version' => '0', 'url_version' => 'all', 'search_javascript' => true, 'meta_description' => $meta_description]);

        }
        else
        {
            return View::make('search.modpack', ['chosen' => true, 'title' => $title, 'search_javascript' => true]);
        }
    }

    public function postModpackSearch()
    {
        $input = Input::only('mods', 'tags', 'mc_version');
        $version = $input['mc_version'];

        if (!$version)
        {
            $url_version = 'all';
        }

        $mods_string = '';
        $tags_string = '';
        $mod_select_array[] = [];

        if ($version)
        {
            $minecraft_version = MinecraftVersion::find($version);
            $mods = $minecraft_version->mods;

            $url_version = preg_replace('/\./', '-', $minecraft_version->name);

            foreach ($mods as $mod)
            {
                $id = $mod->id;
                $mod_select_array[$id] = $mod->name;
            }

            if ($input['mods'])
            {
                foreach ($input['mods'] as $mod)
                {
                    $mods_string .= $mod . ',';
                }

                $mods_string = rtrim($mods_string, ',');
            }
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
        else
        {
            $table_javascript = '/api/table/modpackfinder_'. $url_version .'.json';
        }

        return View::make('search.modpack', ['chosen' => true, 'mc_version' => $version, 'url_version' => $url_version,
            'results' => true, 'table_javascript' => $table_javascript, 'selected_mods' => $input['mods'], 'mods' => $mod_select_array,
            'selected_tags' => $input['tags'], 'search_javascript' => true]);
    }
}