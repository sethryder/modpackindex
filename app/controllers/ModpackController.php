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

        $modpack = Modpack::where('slug', '=', $slug)->first();
        $launcher = $modpack->launcher;
        $creators = $modpack->creators;

        $raw_links = [
            'website'       => $modpack->website,
            'download_link' => $modpack->download_link,
            'donate_link'  => $modpack->donate_link,
            'wiki_link'     => $modpack->wiki_link,
        ];

        $links = [];

        $title = $modpack->name . ' - Modpack - '. $this->site_name;

        foreach ($raw_links as $index => $link)
        {
            if ($link != '')
            {
                $links["$index"] = $link;
            }
        }

        return View::make('modpacks.detail', array('table_javascript' => $table_javascript, 'modpack' => $modpack,
            'links' => $links, 'launcher' => $launcher, 'creators' => $creators, 'title' => $title));
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

        $input = Input::only('name', 'launcher', 'mods', 'creators', 'deck', 'website', 'download_link', 'donate_link', 'wiki_link', 'description', 'slug');

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

                $mods = $minecraft_version->mods;

                foreach ($mods as $mod)
                {
                    $id = $mod->id;
                    $mod_select_array[$id] = $mod->name;
                }

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

        foreach ($modpack->creators as $c)
        {
            $selected_creators[] = $c->id;
        }

        return View::make('modpacks.edit', ['title' => $title, 'modpack' => $modpack, 'mods' => $mod_select_array,
            'selected_mods' => $selected_mods, 'selected_creators' => $selected_creators, 'chosen' => true]);
    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $mod_select_array = [];
        $selected_mods = [];
        $selected_creators = [];

        $title = 'Add A Modpack - ' . $this->site_name;
        $modpack = Modpack::find($id);
        $minecraft_version = MinecraftVersion::where('id', '=', $modpack->minecraft_version_id)->first();
        $creators = $modpack->creators;
        $mods = $modpack->mods;

        $input = Input::only('name', 'launcher', 'selected_mods', 'selected_creators', 'deck', 'website', 'download_link', 'donate_link', 'wiki_link', 'description', 'slug');

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

                $version_mods = $minecraft_version->mods;
                $updated_modpack = Modpack::find($modpack->id);

                foreach ($updated_modpack->mods as $m)
                {
                    $selected_mods[] = $m->id;
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

                return View::make('modpacks.edit', ['title' => $title, 'chosen' => true, 'success' => true,
                    'modpack'=> $updated_modpack, 'version' => $minecraft_version->name, 'mods' => $mod_select_array,
                    'selected_mods' => $selected_mods, 'selected_creators' => $selected_creators]);
            }
            else
            {
                return Redirect::to('/modpack/edit/'.$modpack->id)->withErrors(['message' => 'Unable to edit modpack.'])->withInput();
            }

        }
    }

   /* public function getModsJquery()
    {
        $mods_array = [];
        $mods = Mod::all();

        foreach ($mods as $mod)
        {
            $versions = $mod->versions;

            foreach($versions as $version)
            {
                $name = $version->name;
                $friendly_name = preg_replace('/\./', '-', $name);
                if (array_key_exists($friendly_name, $mods_array))
                {
                    if (!in_array($mod->name, $mods_array["$friendly_name"]))
                    {
                        $id = $mod->id;
                        $mods_array["$friendly_name"]["$id"] = $mod->name;
                    }
                }
                else
                {
                    $id = $mod->id;
                    $mods_array["$friendly_name"]["$id"] = $mod->name;
                }

            }
        }

        return View::make('modpacks.mod_select_options', ['mods' => $mods_array]);
    }*/
}