<?php

class ModpackController extends BaseController
{
    public function getModpackVersion($version='all')
    {
        $table_javascript = '/api/table/modpacks_'. $version .'.json';
        $version = preg_replace('/-/', '.', $version);

        if ($version == 'all') $version = 'All';

        $title = $version . ' Modpacks - '. $this->site_name;

        return View::make('modpacks.list', array('table_javascript' => $table_javascript, 'version' => $version,
            'title'=> $title));
    }

    public function getModpack($version, $slug)
    {
        $table_javascript = '/api/table/modpackmods_'.$version.'/'. $slug .'.json';
        $friendly_version = preg_replace('/-/', '.', $version);

        $modpack = Modpack::where('slug', '=', $slug)->first();

        if (!$modpack)
        {
            $redirect = new URLRedirect;
            $do_redirect = $redirect->getRedirect(Request::path());

            if ($do_redirect)
            {
                return Redirect::to($do_redirect->target, 301);
            }

            App::abort(404);
        }

        $launcher = $modpack->launcher;
        $creators = $modpack->creators;
        $pack_code = $modpack->code;
        $tags = $modpack->tags;
        $twitch_streams = $modpack->twitchStreams()->orderBy('viewers', 'desc')->get();
        $lets_plays = $modpack->youtubeVideos()->where('category_id', 1)->get();

        $raw_links = [
            'website'       => $modpack->website,
            'download_link' => $modpack->download_link,
            'donate_link'  => $modpack->donate_link,
            'wiki_link'     => $modpack->wiki_link,
        ];

        $links = [];

        $title = $modpack->name . ' - ' . $friendly_version . ' Modpack - '. $this->site_name;
        $meta_description = $modpack->deck;

        foreach ($raw_links as $index => $link)
        {
            if ($link != '')
            {
                $links["$index"] = $link;
            }
        }

        return View::make('modpacks.detail', array('table_javascript' => $table_javascript, 'modpack' => $modpack,
            'links' => $links, 'launcher' => $launcher, 'creators' => $creators, 'tags' => $tags, 'title' => $title,
            'meta_description' => $meta_description, 'pack_code' => $pack_code, 'version' => $version,
            'twitch_streams' => $twitch_streams, 'lets_plays' => $lets_plays, 'sticky_tabs' => true));
    }

    public function getCompare()
    {
        $results = false;
        $error = false;
        $modpacks = [];

        $input = Input::only('modpacks');
        $modpacks_input = $input['modpacks'];

        $title = 'Compare Modpacks - '. $this->site_name;
        $meta_description = 'Compare mods between two or more modpacks. Select the modpacks you are interested in below and we will generate a table comparing the mods in each pack.';


        if (!$input['modpacks'])
        {
            return View::make('modpacks.compare', ['chosen' => true, 'title' => $title, 'meta_description' => $meta_description,
                'error' => $error, 'results' => $results, 'selected_modpacks' => false]);
        }

        $table_javascript = '/api/table/compare_all.json?modpacks=' . $modpacks_input;

        $modpack_ids = explode(',', $modpacks_input);

        foreach ($modpack_ids as $id)
        {
            $modpack = Modpack::find($id);
            $modpacks[$id] = '<a href=/modpack/' . preg_replace('/\./', '-', $modpack->version->name) . '/' . $modpack->slug . '>' . $modpack->name . '</a>';
        }

        if (count($modpacks) >= 2)
        {
            $results = true;
        }
        else
        {
            $error = 'You must select two or more modpacks!';
        }

        return View::make('modpacks.compare', ['modpacks' => $modpacks, 'table_javascript' => $table_javascript,
            'chosen' => true, 'selected_modpacks' => $modpack_ids, 'results' => $results,
            'title' => $title, 'meta_description' => $meta_description, 'error' => $error, 'table_fixed_header' => true]);
    }

    public function postCompare()
    {
        $input = Input::only('modpacks');

        $forward_string = '';

        foreach ($input['modpacks'] as $modpack_id)
        {
            $forward_string .= $modpack_id . ',';
        }

        return Redirect::to('/modpacks/compare?modpacks=' . rtrim($forward_string, ','));
    }

    public function getAdd($version)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $mod_select_array = [];
        $url_version = $version;
        $version = preg_replace('/-/', '.', $version);
        $title = 'Add A '. $version .' Modpack - '. $this->site_name;
        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();

        $mods = $minecraft_version->mods;

        foreach ($mods as $mod)
        {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        return View::make('modpacks.add', ['chosen' => true, 'mods' => $mod_select_array, 'title' => $title,
            'version' => $version, 'url_version' => $url_version]);
    }

    public function postAdd($version)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $url_version = $version;
        $version = preg_replace('/-/', '.', $version);
        $title = 'Add A Modpack - ' . $this->site_name;
        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();

        $input = Input::only('name', 'launcher', 'mods', 'tags', 'creators', 'deck', 'website', 'download_link',
            'donate_link', 'wiki_link', 'description', 'slug');

        $messages = [
            'unique' => 'This mod already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:modpacks,name',
                'launcher' => 'required',
                'mods' => 'required',
                'creators' => 'required',
                'deck'  => 'required',
                'website' => 'url',
                'download_url' => 'url',
                'wiki_url' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/modpack/' . $url_version . '/add')->withErrors($validator)->withInput();
        }
        else
        {
            $modpack = new Modpack;

            $modpack->name = $input['name'];
            $modpack->launcher_id = $input['launcher'];
            $modpack->minecraft_version_id = $minecraft_version->id;
            $modpack->deck = $input['deck'];
            $modpack->website = $input['website'];
            $modpack->download_link = $input['download_link'];
            $modpack->donate_link = $input['donate_link'];
            $modpack->wiki_link = $input['wiki_link'];
            $modpack->description = $input['description'];

            if ($input['slug'] == '')
            {
                $slug = Str::slug($input['name']);
            }
            else
            {
                $slug = $input['slug'];
            }

            $modpack->slug = $slug;
            $modpack->last_ip = Request::getClientIp();

            $success = $modpack->save();

            if ($success)
            {
                foreach ($input['creators'] as $creator)
                {
                    $modpack->creators()->attach($creator);
                }

                foreach ($input['mods'] as $mod)
                {
                    $modpack->mods()->attach($mod);
                }

                foreach ($input['tags'] as $tag)
                {
                    $modpack->tags()->attach($tag);
                }

                $mods = $minecraft_version->mods;

                foreach ($mods as $mod)
                {
                    $id = $mod->id;
                    $mod_select_array[$id] = $mod->name;
                }

                Cache::tags('modpacks')->flush();
                Cache::tags('modpackmods')->flush();
                Cache::tags('modmodpacks')->flush();
                Queue::push('BuildCache');

                return View::make('modpacks.add', ['title' => $title, 'chosen' => true, 'success' => true, 'version' => $version,
                    'url_version' => $url_version, 'mods' => $mod_select_array]);
            }
            else
            {
                return Redirect::to('/modpack/' . $url_version . '/add')->withErrors(['message' => 'Unable to add modpack.'])->withInput();
            }

        }
    }

    public function getEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Edit A Modpack - ' . $this->site_name;
        $selected_mods = [];
        $selected_tags = [];
        $selected_creators = [];
        $mod_select_array = [];

        $modpack = Modpack::find($id);

        $minecraft_version = MinecraftVersion::where('id', '=', $modpack->minecraft_version_id)->first();

        $version_mods = $minecraft_version->mods;

        foreach ($version_mods as $mod)
        {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        foreach ($modpack->mods as $m)
        {
            $selected_mods[] = $m->id;
        }

        foreach ($modpack->tags as $t)
        {
            $selected_tags[] = $t->id;
        }

        foreach ($modpack->creators as $c)
        {
            $selected_creators[] = $c->id;
        }

        return View::make('modpacks.edit', ['title' => $title, 'modpack' => $modpack, 'mods' => $mod_select_array,
            'selected_mods' => $selected_mods, 'selected_creators' => $selected_creators, 'selected_tags' => $selected_tags,
            'chosen' => true]);
    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $mod_select_array = [];
        $selected_mods = [];
        $selected_tags = [];
        $selected_creators = [];

        $title = 'Add A Modpack - ' . $this->site_name;
        $modpack = Modpack::find($id);
        $minecraft_version = MinecraftVersion::where('id', '=', $modpack->minecraft_version_id)->first();
        $creators = $modpack->creators;
        $mods = $modpack->mods;
        $tags = $modpack->tags;

        $input = Input::only('name', 'launcher', 'selected_mods', 'selected_creators', 'selected_tags', 'deck', 'website',
            'download_link', 'donate_link', 'wiki_link', 'description', 'slug');

        $messages = [
            'unique' => 'This modpack already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:modpacks,name,' . $modpack->id,
                'launcher' => 'required',
                'selected_mods' => 'required',
                'selected_creators' => 'required',
                'deck'  => 'required',
                'website' => 'url',
                'download_url' => 'url',
                'wiki_url' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/modpack/edit/'.$modpack->id)->withErrors($validator)->withInput();
        }
        else
        {
            $modpack->name = $input['name'];
            $modpack->launcher_id = $input['launcher'];
            $modpack->deck = $input['deck'];
            $modpack->website = $input['website'];
            $modpack->download_link = $input['download_link'];
            $modpack->donate_link = $input['donate_link'];
            $modpack->wiki_link = $input['wiki_link'];
            $modpack->description = $input['description'];

            if ($input['slug'] == '' || $input['slug'] == $modpack->slug)
            {
                $slug = Str::slug($input['name']);
            }
            else
            {
                $slug = $input['slug'];
            }

            $modpack->slug = $slug;
            $modpack->last_ip = Request::getClientIp();

            $success = $modpack->save();

            if ($success)
            {
                foreach($creators as $c)
                {
                    $modpack->creators()->detach($c->id);
                }
                $modpack->creators()->attach($input['selected_creators']);

                foreach ($mods as $m)
                {
                    $modpack->mods()->detach($m->id);
                }
                $modpack->mods()->attach($input['selected_mods']);

                foreach ($tags as $t)
                {
                    $modpack->tags()->detach($t->id);
                }
                if ($input['selected_tags']) $modpack->tags()->attach($input['selected_tags']);

                $version_mods = $minecraft_version->mods;
                $updated_modpack = Modpack::find($modpack->id);

                foreach ($updated_modpack->mods as $m)
                {
                    $selected_mods[] = $m->id;
                }

                foreach ($updated_modpack->tags as $t)
                {
                    $selected_tags[] = $t->id;
                }

                foreach ($updated_modpack->creators as $c)
                {
                    $selected_creators[] = $c->id;
                }

                foreach ($version_mods as $mod)
                {
                    $id = $mod->id;
                    $mod_select_array[$id] = $mod->name;
                }

                foreach ($mods as $mod)
                {
                    $id = $mod->id;
                    $mod_select_array[$id] = $mod->name;
                }

                Cache::tags('modpacks')->flush();
                Cache::tags('modpackmods')->flush();
                Cache::tags('modmodpacks')->flush();
                Queue::push('BuildCache');

                return View::make('modpacks.edit', ['title' => $title, 'chosen' => true, 'success' => true,
                    'modpack'=> $updated_modpack, 'version' => $minecraft_version->name, 'mods' => $mod_select_array,
                    'selected_mods' => $selected_mods, 'selected_creators' => $selected_creators,
                    'selected_tags' => $selected_tags]);
            }
            else
            {
                return Redirect::to('/modpack/edit/'.$modpack->id)->withErrors(['message' => 'Unable to edit modpack.'])->withInput();
            }

        }
    }
}