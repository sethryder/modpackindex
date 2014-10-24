<?php

class ModController extends BaseController
{
    public function getAll()
    {
        return Mod::all();
    }

    public function getModVersion($version='all')
    {
        $table_javascript = '/api/table/mods_'. $version .'.json';
        $version = preg_replace('/-/', '.', $version);

        if ($version == 'all') $version = 'All';

        $title = $version . ' Mods - '. $this->site_name;

        return View::make('mods.list', array('table_javascript' => $table_javascript, 'version' => $version, 'title' => $title));
    }

    public function getMod($slug)
    {
        $table_javascript = '/api/table/modmodpacks_0/'. $slug .'.json';

        $mod = Mod::where('slug', '=', $slug)->first();

        $raw_links = [
            'website'       => $mod->website,
            'download_link' => $mod->download_link,
            'donate_link'  => $mod->donate_link,
            'wiki_link'     => $mod->wiki_link,
        ];

        $links = [];

        foreach ($raw_links as $index => $link)
        {
            if ($link != '')
            {
                $links["$index"] = $link;
            }
        }

        $title = $mod->name . ' - Mod - '. $this->site_name;

        return View::make('mods.detail', array('table_javascript' => $table_javascript, 'mod' => $mod, 'links' => $links, 'title' => $title));
    }

    public function getAdd()
    {
        $versions = MinecraftVersion::all();

        return View::make('mods.add', ['chosen' => true, 'versions' => $versions]);
    }

    public function postAdd()
    {
        $versions = MinecraftVersion::all();
        $title = 'Add A Mod - ' . $this->site_name;

        $input = Input::only('name', 'versions', 'author', 'deck', 'website', 'download_link', 'donate_link', 'wiki_link', 'description', 'slug');

        $messages = [
            'unique' => 'This mod already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:mods,name',
                'author' => 'required',
                'versions' => 'required',
                'deck'  => 'required',
                'website' => 'url',
                'download_url' => 'url',
                'wiki_url' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/mod/add/')->withErrors($validator)->withInput();
        }
        else
        {
            $mod = new Mod;

            $mod->name = $input['name'];
            $mod->deck = $input['deck'];
            $mod->website = $input['website'];
            $mod->download_link = $input['download_link'];
            $mod->donate_link = $input['donate_link'];
            $mod->wiki_link = $input['wiki_link'];
            $mod->description = $input['description'];

            if ($input['slug'] == '')
            {
                $slug = Str::slug($input['name']);
            }
            else
            {
                $slug = $input['slug'];
            }

            $mod->slug = $slug;
            $mod->last_ip = Request::getClientIp();

            $success = $mod->save();

            if ($success)
            {
                foreach ($input['author'] as $author)
                {
                    $mod->authors()->attach($author);
                }

                foreach ($input['versions'] as $version)
                {
                    $mod->versions()->attach($version);
                }

                return View::make('mods.add', ['title' => $title, 'chosen' => true, 'success' => true, 'versions' => $versions]);
            }
            else
            {
                return Redirect::to('/author/add/')->withErrors(['message' => 'Unable to add mod.'])->withInput();
            }

        }
    }

    public function getTable($version)
    {
        $mods = Mod::all();
        $table_mods = array();

        foreach ($mods as $mod)
        {
            $table_mods[] = array(
                'name' => $mod->name,
                ''
            );
        }

        print_r($mods[0]);
    }
}