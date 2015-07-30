<?php

class SearchController extends BaseController
{
    public function getModpackSearch()
    {
        $results = false;
        $query_array = [];
        $mod_select_array = [];
        $version_select = [];
        $selected_mods = [];
        $selected_tags = [];
        $url_version = 'all';

        $title = 'Modpack Finder - ' . $this->site_name;
        $meta_description = 'Find and discover amazing modpacks. Refine your search for packs that include specific mods and tags.';

        $input = Input::only('tag', 'tags', 'mods', 'version');

        $minecraft_versions = MinecraftVersion::where('name', '!=', '1.8')->get();
        $tags = ModpackTag::all();

        foreach ($minecraft_versions as $v) {
            $version_slug = preg_replace('/\./', '-', $v->name);
            $version_select[$version_slug] = $v->name;
        }

        if ($input['version'] && $input['version'] != 'all') {
            $url_version = $input['version'];
            $version = preg_replace('/\-/', '.', $url_version);

            $minecraft_version = MinecraftVersion::where('name', $version)->first();
            $mods = $minecraft_version->mods;

            foreach ($mods as $mod) {
                $id = $mod->id;
                $mod_select_array[$id] = $mod->name;
            }

            if ($input['mods']) {
                $mods_string = '';

                $exploded_mods = explode(',', strtolower($input['mods']));

                foreach ($exploded_mods as $mod) {
                    $mods_string .= $mod . ',';
                    $selected_mods[] = $mod;
                }

                $query_array[] = 'mods=' . rtrim($mods_string, ',');
            }
        }

        if ($input['version'] == 'all') {
            $results = true;
        }

        if ($input['tags']) {
            $tags_array = [];
            $tags_javascript_string = '';

            foreach ($tags as $t) {
                $tags_array[$t->slug] = $t->id;
            }

            $exploded_tags = explode(',', strtolower($input['tags']));

            foreach ($exploded_tags as $t) {
                if (array_key_exists($t, $tags_array)) {
                    $tags_javascript_string .= $tags_array[$t] . ',';
                    $selected_tags[] = $t;
                }
            }

            $query_array[] = 'tags=' . rtrim($tags_javascript_string, ',');
        }

        if ($input['tag']) {
            $tag = ModpackTag::where('slug', $input['tag'])->first();

            if (!$tag) {
                return Redirect::to('/modpack/finder');
            }

            $title = $tag->name . ' Modpacks - Pack Finder - ' . $this->site_name;
            $meta_description = 'Find and discover ' . $tag->name . ' Modpacks. Refine your search for packs that include specific mods.';

            $selected_tags[] = $tag->slug;
            $query_array[] = 'tags=' . $tag->id;
        }

        $table_javascript = '/api/table/modpackfinder_' . $url_version . '.json';

        $query_count = 0;

        foreach($query_array as $q) {
            if ($query_count == 0) {
                $table_javascript .= '?';
            } else {
                $table_javascript .= '&';
            }
            $table_javascript .= $q;

            $results = true;
            $query_count++;
        }

        return View::make('search.modpack', [
            'chosen' => true,
            'title' => $title,
            'results' => $results,
            'table_javascript' => $table_javascript,
            'mods' => $mod_select_array,
            'selected_mods' => $selected_mods,
            'selected_tags' => $selected_tags,
            'selected_version' => $input['version'],
            'version_select' => $version_select,
            'url_version' => $url_version,
            'search_javascript' => true,
            'meta_description' => $meta_description
        ]);
    }

    public function postModpackSearch()
    {
        $query_array = [];
        $query_string = '';

        $input = Input::only('mods', 'tags', 'mc_version');

        if ($input['mc_version']) {
            $query_array[] = 'version=' . $input['mc_version'];
        }

        if ($input['tags']) {
            $tag_string = 'tags=';
            foreach ($input['tags'] as $t) {
                $tag_string .= $t . ',';
            }
            $query_array[] = rtrim($tag_string, ',');
        }

        if ($input['mods']) {
            $mod_string = 'mods=';
            foreach ($input['mods'] as $m) {
                $mod_string .= $m . ',';
            }
            $query_array[] = rtrim($mod_string, ',');
        }

        $query_count = 0;

        foreach($query_array as $q) {
            if ($query_count == 0) {
                $query_string .= '?';
            } else {
                $query_string .= '&';
            }
            $query_string .= $q;

            $query_count++;
        }

        return Redirect::to('/modpack/finder' . $query_string);
    }
}