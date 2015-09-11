<?php

class ModpackController extends BaseController
{
    use \App\TraitCommon;

    public function getModpackVersion($version = 'all')
    {
        $table_javascript = route('tdf', ['modpacks', $version]);
        $version = $this->getVersion($version);

        if ($version == 'all') {
            $version = 'All';
        }

        $title = $version . ' Modpacks - ' . $this->site_name;

        return View::make('modpacks.list', [
            'table_javascript' => $table_javascript,
            'version' => $version,
            'title' => $title
        ]);
    }

    public function getModpack($version, $slug)
    {
        $mods_javascript = route('tdf_name', ['modpackmods', $version, $slug]);
        $friendly_version = $this->getVersion($version);

        if (strpos($version, '.')) {
            return Redirect::action('ModpackController@getModpack', [$this->getVersionSlug($version), $slug], 301);
        }

        $modpack = Modpack::where('slug', '=', $slug)->first();

        if (!$modpack) {
            $redirect = new URLRedirect;
            $do_redirect = $redirect->getRedirect(Request::path());

            if ($do_redirect) {
                return Redirect::to($do_redirect->target, 301);
            }

            App::abort(404);
        }

        $can_edit = false;
        $has_servers = false;

        if (Auth::check()) {
            $maintainer = $modpack->maintainers()->where('user_id', Auth::id())->first();

            if ($maintainer) {
                $can_edit = true;
            }
        }

        $launcher = $modpack->launcher;
        $creators = $modpack->creators;

        $creators_formatted = implode(', ', array_map(function ($creator) {
            return $creator['name'];
        }, $creators->toArray()));

        $pack_code = $modpack->code;
        $tags = $modpack->tags;

        $tags_formatted = implode(', ', array_map(function ($tag) {
            return link_to(action('SearchController@getModpackSearch') . '?tag=' . $tag['slug'], $tag['name'],
                ['title' => $tag['deck']]);
        }, $tags->toArray()));

        $server_count = $modpack->servers()->where('active', 1)->count();
        $twitch_streams = $modpack->twitchStreams()->orderBy('viewers', 'desc')->get();
        $lets_plays = $modpack->youtubeVideos()->where('category_id', 1)->get();

        if ($server_count > 0) {
            $has_servers = true;
        }

        if ($modpack->is_deprecated) {
            if ($modpack->sequel_modpack_id) {
                $sequel_modpack = Modpack::find($modpack->sequel_modpack_id);
                $sequel_version = MinecraftVersion::where('id', $modpack->minecraft_version_id)->first();

                $modpack->sequel_modpack_name = $sequel_modpack->name;
                $modpack->sequel_modpack_slug = $sequel_modpack->slug;
                $modpack->sequel_modpack_version = $this->getVersionSlug($sequel_version->name);
            }
        }

        $server_javascript = route('tdf', ['servers', 'all']) . '?modpack=' . $modpack->id;

        $raw_links = [
            'website' => $modpack->website,
            'download_link' => $modpack->download_link,
            'donate_link' => $modpack->donate_link,
            'wiki_link' => $modpack->wiki_link,
        ];

        $links = [];

        foreach ($raw_links as $index => $link) {
            if (!empty($link)) {
                $links[] = ['type' => $index, 'link' => $link];
            }
        }

        $links_formatted = implode(' | ', array_map(function ($link) {
            if ($link['type'] == 'website') {
                return "<a href='{$link['link']}'><i class='fa fa-external-link'></i>Website</a>";
            }

            if ($link['type'] == 'download_link') {
                return "<a href='{$link['link']}'><i class='fa fa-download'></i>Download</a>";
            }

            if ($link['type'] == 'donate_link') {
                return "<a href='{$link['link']}'><i class='fa fa-dollar'></i>Donate</a>";
            }

            if ($link['type'] == 'wiki_link') {
                return "<a href='{$link['link']}'><i class='fa fa-book'></i>Wiki</a>";
            }

            return '';
        }, $links));

        $table_javascript = [
            $mods_javascript,
            $server_javascript
        ];

        $markdown_html = Parsedown::instance()->setBreaksEnabled(true)->text(strip_tags($modpack->description));
        $modpack_description = str_replace('<table>', '<table class="table table-striped table-bordered">',
            $markdown_html);

        $title = $modpack->name . ' - ' . $friendly_version . ' Modpack - ' . $this->site_name;
        $meta_description = $modpack->deck;

        return View::make('modpacks.detail', [
            'table_javascript' => $table_javascript,
            'modpack' => $modpack,
            'modpack_description' => $modpack_description,
            'links' => $links,
            'links_formatted' => $links_formatted,
            'launcher' => $launcher,
            'creators' => $creators,
            'creators_formatted' => $creators_formatted,
            'tags' => $tags,
            'tags_formatted' => $tags_formatted,
            'servers' => $has_servers,
            'title' => $title,
            'meta_description' => $meta_description,
            'pack_code' => $pack_code,
            'version' => $version,
            'twitch_streams' => $twitch_streams,
            'lets_plays' => $lets_plays,
            'has_servers' => $has_servers,
            'can_edit' => $can_edit,
            'sticky_tabs' => true
        ]);
    }

    public function getCompare()
    {
        $results = false;
        $error = false;
        $modpacks = [];
        $json_string = '';

        $input = Input::only('modpacks');

        $title = 'Compare Modpacks - ' . $this->site_name;
        $meta_description = 'Compare mods between two or more modpacks. Select the modpacks you are interested in below and we will generate a table comparing the mods in each pack.';

        if (!$input['modpacks']) {
            return View::make('modpacks.compare', [
                'chosen' => true,
                'title' => $title,
                'meta_description' => $meta_description,
                'error' => $error,
                'results' => $results,
                'selected_modpacks' => false
            ]);
        }

        $modpack_ids = explode(',', $input['modpacks']);

        foreach ($modpack_ids as $id) {
            $modpack = Modpack::find($id);

            if ($modpack) {
                $modpacks[$id] = link_to_action('ModpackController@getModpack', $modpack->name,
                    [$this->getVersion($modpack->version->name), $modpack->slug]);
                $json_string .= $modpack->id . ',';
            }
        }

        $table_javascript = route('tdf', ['compare', 'all']) . '?modpacks=' . rtrim($json_string, ',');

        if (count($modpacks) >= 2) {
            $results = true;
        } else {
            $error = 'You must select two or more modpacks!';
        }

        return View::make('modpacks.compare', [
            'modpacks' => $modpacks,
            'table_javascript' => $table_javascript,
            'chosen' => true,
            'selected_modpacks' => $modpack_ids,
            'results' => $results,
            'title' => $title,
            'meta_description' => $meta_description,
            'error' => $error,
            'table_fixed_header' => true
        ]);
    }

    public function postCompare()
    {
        $input = Input::only('modpacks');

        $forward_string = '';

        if (!$input['modpacks']) {
            return Redirect::action('ModpackController@getCompare');
        }

        foreach ($input['modpacks'] as $modpack_id) {
            $forward_string .= $modpack_id . ',';
        }

        return Redirect::to(action('ModpackController@getCompare') . '?modpacks=' . rtrim($forward_string, ','));
    }

    public function getAdd($version)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $mod_select_array = [];
        $url_version = $version;
        $version = $this->getVersion($version);
        $title = 'Add A ' . $version . ' Modpack - ' . $this->site_name;
        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();

        $mods = $minecraft_version->mods;

        foreach ($mods as $mod) {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        natcasesort($mod_select_array);

        return View::make('modpacks.add', [
            'chosen' => true,
            'mods' => $mod_select_array,
            'title' => $title,
            'version' => $version,
            'url_version' => $url_version
        ]);
    }

    public function postAdd($version)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $url_version = $version;
        $version = $this->getVersion($version);
        $title = 'Add A Modpack - ' . $this->site_name;
        $minecraft_version = MinecraftVersion::where('name', '=', $version)->first();

        $input = Input::only('name', 'launcher', 'mods', 'tags', 'creators', 'deck', 'website', 'download_link',
            'donate_link', 'wiki_link', 'description', 'is_deprecated', 'sequel_modpack_id', 'slug');

        $messages = [
            'unique' => 'This mod already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input, [
            'name' => 'required|unique:modpacks,name',
            'launcher' => 'required',
            'mods' => 'required',
            'creators' => 'required',
            'deck' => 'required',
            'website' => 'url',
            'download_url' => 'url',
            'wiki_url' => 'url',
            'donate_link' => 'url',
        ], $messages);

        if ($validator->fails()) {
            return Redirect::action('ModpackController@getAdd', [$url_version])->withErrors($validator)->withInput();
        }

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

        if ($input['is_deprecated'] == 1) {
            $modpack->is_deprecated = 1;
        } else {
            $modpack->is_deprecated = 0;
        }

        $modpack->sequel_modpack_id = $input['sequel_modpack_id'];

        if ($input['slug'] == '') {
            $slug = Str::slug($input['name']);
        } else {
            $slug = $input['slug'];
        }

        $modpack->slug = $slug;
        $modpack->last_ip = Request::getClientIp();

        $success = $modpack->save();

        if ($success) {
            foreach ($input['creators'] as $creator) {
                $modpack->creators()->attach($creator);
            }

            foreach ($input['mods'] as $mod) {
                $modpack->mods()->attach($mod);
            }

            foreach ($input['tags'] as $tag) {
                $modpack->tags()->attach($tag);
            }

            $mods = $minecraft_version->mods;

            foreach ($mods as $mod) {
                $id = $mod->id;
                $mod_select_array[$id] = $mod->name;
            }

            Cache::tags('modpacks')->flush();
            Cache::tags('modpackmods')->flush();
            Cache::tags('modmodpacks')->flush();
            Queue::push('BuildCache');

            return View::make('modpacks.add', [
                'title' => $title,
                'chosen' => true,
                'success' => true,
                'version' => $version,
                'url_version' => $url_version,
                'mods' => $mod_select_array
            ]);
        }

        return Redirect::action('ModpackController@getAdd', [$url_version])
            ->withErrors(['message' => 'Unable to add modpack.'])->withInput();
    }

    public function getEdit($id)
    {
        $title = 'Edit A Modpack - ' . $this->site_name;
        $selected_mods = [];
        $selected_tags = [];
        $selected_creators = [];
        $selected_maintainers = [];
        $mod_select_array = [];
        $can_edit_maintainers = false;

        $modpack = Modpack::find($id);

        if (Auth::check()) {
            $maintainer = $modpack->maintainers()->where('user_id', Auth::id())->first();

            if (!$maintainer) {
                if ($this->checkRoute()) {
                    $can_edit_maintainers = true;
                } else {
                    return Redirect::route('index');
                }
            }
        } else {
            return Redirect::route('index');
        }

        $minecraft_version = MinecraftVersion::where('id', '=', $modpack->minecraft_version_id)->first();

        $version_mods = $minecraft_version->mods;

        foreach ($version_mods as $mod) {
            $id = $mod->id;
            $mod_select_array[$id] = $mod->name;
        }

        foreach ($modpack->mods as $m) {
            $selected_mods[] = $m->id;
        }

        foreach ($modpack->tags as $t) {
            $selected_tags[] = $t->id;
        }

        foreach ($modpack->creators as $c) {
            $selected_creators[] = $c->id;
        }

        foreach ($modpack->maintainers as $m) {
            $selected_maintainers[] = $m->id;
        }

        natcasesort($mod_select_array);

        return View::make('modpacks.edit', [
            'title' => $title,
            'modpack' => $modpack,
            'mods' => $mod_select_array,
            'selected_mods' => $selected_mods,
            'selected_creators' => $selected_creators,
            'selected_tags' => $selected_tags,
            'selected_maintainers' => $selected_maintainers,
            'can_edit_maintainers' => $can_edit_maintainers,
            'chosen' => true
        ]);
    }

    public function postEdit($id)
    {
        $mod_select_array = [];
        $selected_mods = [];
        $selected_tags = [];
        $selected_creators = [];
        $selected_maintainers = [];
        $can_edit_maintainers = false;

        $title = 'Edit A Modpack - ' . $this->site_name;
        $modpack = Modpack::find($id);

        if (Auth::check()) {
            $maintainer = $modpack->maintainers()->where('user_id', Auth::id())->first();

            if (!$maintainer) {
                if ($this->checkRoute()) {
                    $can_edit_maintainers = true;
                } else {
                    return Redirect::route('index');
                }
            }
        } else {
            return Redirect::route('index');
        }

        $minecraft_version = MinecraftVersion::where('id', '=', $modpack->minecraft_version_id)->first();
        $creators = $modpack->creators;
        $mods = $modpack->mods;
        $tags = $modpack->tags;
        $maintainers = $modpack->maintainers;

        $input = Input::only('name', 'launcher', 'selected_mods', 'selected_creators', 'selected_tags',
            'selected_maintainers', 'deck', 'website', 'download_link', 'donate_link', 'wiki_link', 'description',
            'is_deprecated', 'sequel_modpack_id', 'slug');

        $messages = [
            'unique' => 'This modpack already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input, [
            'name' => 'required|unique:modpacks,name,' . $modpack->id,
            'launcher' => 'required',
            'selected_mods' => 'required',
            'selected_creators' => 'required',
            'deck' => 'required',
            'website' => 'url',
            'download_url' => 'url',
            'wiki_url' => 'url',
            'donate_link' => 'url',
        ], $messages);

        if ($validator->fails()) {
            return Redirect::action('ModController@getEdit', [$modpack->id])->withErrors($validator)->withInput();
        }

        $modpack->name = $input['name'];
        $modpack->launcher_id = $input['launcher'];
        $modpack->deck = $input['deck'];
        $modpack->website = $input['website'];
        $modpack->download_link = $input['download_link'];
        $modpack->donate_link = $input['donate_link'];
        $modpack->wiki_link = $input['wiki_link'];
        $modpack->description = $input['description'];

        if ($input['is_deprecated'] == 1) {
            $modpack->is_deprecated = 1;
        } else {
            $modpack->is_deprecated = 0;
        }

        $modpack->sequel_modpack_id = $input['sequel_modpack_id'];

        if ($input['slug'] == '' || $input['slug'] == $modpack->slug) {
            $slug = Str::slug($input['name']);
        } else {
            $slug = $input['slug'];
        }

        $modpack->slug = $slug;
        $modpack->last_ip = Request::getClientIp();

        $success = $modpack->save();

        if ($success) {
            foreach ($creators as $c) {
                $modpack->creators()->detach($c->id);
            }
            $modpack->creators()->attach($input['selected_creators']);

            foreach ($mods as $m) {
                $modpack->mods()->detach($m->id);
            }
            $modpack->mods()->attach($input['selected_mods']);

            foreach ($tags as $t) {
                $modpack->tags()->detach($t->id);
            }
            if ($input['selected_tags']) {
                $modpack->tags()->attach($input['selected_tags']);
            }

            if ($can_edit_maintainers) {
                foreach ($maintainers as $m) {
                    $modpack->maintainers()->detach($m->id);
                }
                if ($input['selected_maintainers']) {
                    $modpack->maintainers()->attach($input['selected_maintainers']);
                }
            }

            $version_mods = $minecraft_version->mods;
            $updated_modpack = Modpack::find($modpack->id);

            foreach ($updated_modpack->mods as $m) {
                $selected_mods[] = $m->id;
            }

            foreach ($updated_modpack->tags as $t) {
                $selected_tags[] = $t->id;
            }

            foreach ($updated_modpack->creators as $c) {
                $selected_creators[] = $c->id;
            }

            foreach ($updated_modpack->maintainers as $m) {
                $selected_maintainers[] = $m->id;
            }

            foreach ($version_mods as $mod) {
                $id = $mod->id;
                $mod_select_array[$id] = $mod->name;
            }

            foreach ($mods as $mod) {
                $id = $mod->id;
                $mod_select_array[$id] = $mod->name;
            }

            Cache::tags('modpacks')->flush();
            Cache::tags('modpackmods')->flush();
            Cache::tags('modmodpacks')->flush();
            Queue::push('BuildCache');

            return View::make('modpacks.edit', [
                'title' => $title,
                'chosen' => true,
                'success' => true,
                'modpack' => $updated_modpack,
                'version' => $minecraft_version->name,
                'mods' => $mod_select_array,
                'selected_mods' => $selected_mods,
                'selected_creators' => $selected_creators,
                'selected_tags' => $selected_tags,
                'selected_maintainers' => $selected_maintainers,
                'can_edit_maintainers' => $can_edit_maintainers,
            ]);
        }

        return Redirect::action('ModController@getEdit', [$modpack->id])
            ->withErrors(['message' => 'Unable to edit modpack.'])->withInput();
    }
}