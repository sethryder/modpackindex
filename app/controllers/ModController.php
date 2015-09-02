<?php

class ModController extends BaseController
{
    use \App\TraitCommon;

    public function getAll()
    {
        return Mod::all();
    }

    public function getModVersion($version = 'all')
    {
        $table_javascript = route('tdf', ['mods', $version]);
        $version = $this->getVersion($version);

        if ($version == 'all') {
            $version = 'All';
        }

        $title = $version . ' Mods - ' . $this->site_name;

        return View::make('mods.list',
            ['table_javascript' => $table_javascript, 'version' => $version, 'title' => $title]);
    }

    public function getMod($slug)
    {
        $table_javascript = route('tdf_name', ['modmodpacks', '0', $slug]);

        $mod = Mod::where('slug', '=', $slug)->first();

        if (!$mod) {
            $redirect = new URLRedirect;
            $do_redirect = $redirect->getRedirect(Request::path());

            if ($do_redirect) {
                return Redirect::to($do_redirect->target, 301);
            }

            App::abort(404);
        }

        $can_edit = false;

        if (Auth::check()) {
            $maintainer = $mod->maintainers()->where('user_id', Auth::id())->first();

            if ($maintainer) {
                $can_edit = true;
            }
        }

        $authors = $mod->authors;
        $spotlights = $mod->youtubeVideos()->where('category_id', 2)->get();
        $tutorials = $mod->youtubeVideos()->where('category_id', 3)->get();

        $raw_links = [
            'website' => $mod->website,
            'download_link' => $mod->download_link,
            'donate_link' => $mod->donate_link,
            'wiki_link' => $mod->wiki_link,
        ];

        $links = [];

        foreach ($raw_links as $index => $link) {
            if ($link != '') {
                $links["$index"] = $link;
            }
        }

        $markdown_html = Parsedown::instance()->setBreaksEnabled(true)->text(strip_tags($mod->description));
        $mod_description = str_replace('<table>', '<table class="table table-striped table-bordered">', $markdown_html);

        $title = $mod->name . ' - Mod - ' . $this->site_name;
        $meta_description = $mod->deck;

        return View::make('mods.detail', [
            'table_javascript' => $table_javascript,
            'mod' => $mod,
            'mod_description' => $mod_description,
            'links' => $links,
            'authors' => $authors,
            'title' => $title,
            'meta_description' => $meta_description,
            'sticky_tabs' => true,
            'spotlights' => $spotlights,
            'tutorials' => $tutorials,
            'can_edit' => $can_edit,
        ]);
    }

    public function getAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $versions = MinecraftVersion::all();

        return View::make('mods.add', ['chosen' => true, 'versions' => $versions]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $versions = MinecraftVersion::all();
        $title = 'Add A Mod - ' . $this->site_name;

        $input = Input::only('name', 'versions', 'author', 'deck', 'website', 'download_link', 'donate_link',
            'wiki_link', 'description', 'slug', 'mod_list_hide');

        $messages = [
            'unique' => 'This mod already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input, [
            'name' => 'required|unique:mods,name',
            'author' => 'required',
            'versions' => 'required',
            'deck' => 'required',
            'website' => 'url',
            'download_url' => 'url',
            'wiki_url' => 'url',
            'donate_link' => 'url',
        ], $messages);

        if ($validator->fails()) {
            return Redirect::to('/mod/add/')->withErrors($validator)->withInput();
        } else {
            $mod = new Mod;

            $mod->name = $input['name'];
            $mod->deck = $input['deck'];
            $mod->website = $input['website'];
            $mod->download_link = $input['download_link'];
            $mod->donate_link = $input['donate_link'];
            $mod->wiki_link = $input['wiki_link'];
            $mod->description = $input['description'];

            if ($input['slug'] == '') {
                $slug = Str::slug($input['name']);
            } else {
                $slug = $input['slug'];
            }

            if ($input['mod_list_hide'] == 1) {
                $mod->mod_list_hide = 1;
            }

            $mod->slug = $slug;
            $mod->last_ip = Request::getClientIp();

            $success = $mod->save();

            if ($success) {
                foreach ($input['author'] as $author) {
                    $mod->authors()->attach($author);
                }

                foreach ($input['versions'] as $version) {
                    $mod->versions()->attach($version);
                }

                Cache::tags('mods')->flush();
                Queue::push('BuildCache');

                return View::make('mods.add',
                    ['title' => $title, 'chosen' => true, 'success' => true, 'versions' => $versions]);
            } else {
                return Redirect::action('ModController@getAdd')->withErrors(['message' => 'Unable to add mod.'])
                    ->withInput();
            }

        }
    }

    public function getEdit($id)
    {
        $title = 'Edit A Mod - ' . $this->site_name;
        $versions = MinecraftVersion::all();
        $selected_versions = [];
        $selected_authors = [];
        $selected_maintainers = [];
        $can_edit_maintainers = false;

        $mod = Mod::find($id);

        if (Auth::check()) {
            $maintainer = $mod->maintainers()->where('user_id', Auth::id())->first();

            if (!$maintainer) {
                if ($this->checkRoute()) {
                    $can_edit_maintainers = true;
                } else {
                    return Redirect::to('/');
                }
            }
        } else {
            return Redirect::to('/');
        }

        foreach ($mod->versions as $v) {
            $selected_versions[] = $v->name;
        }

        foreach ($mod->authors as $a) {
            $selected_authors[] = $a->id;
        }

        foreach ($mod->maintainers as $m) {
            $selected_maintainers[] = $m->id;
        }

        return View::make('mods.edit', [
            'title' => $title,
            'mod' => $mod,
            'versions' => $versions,
            'selected_versions' => $selected_versions,
            'selected_authors' => $selected_authors,
            'selected_maintainers' => $selected_maintainers,
            'chosen' => true,
            'can_edit_maintainers' => $can_edit_maintainers,
        ]);
    }

    public function postEdit($id)
    {
        $selected_versions = [];
        $selected_authors = [];
        $selected_maintainers = [];
        $minecraft_versions = MinecraftVersion::all();
        $title = 'Edit A Mod - ' . $this->site_name;
        $can_edit_maintainers = false;

        $mod = Mod::find($id);

        if (Auth::check()) {
            $maintainer = $mod->maintainers()->where('user_id', Auth::id())->first();

            if (!$maintainer) {
                if ($this->checkRoute()) {
                    $can_edit_maintainers = true;
                } else {
                    return Redirect::to('/');
                }
            }
        } else {
            return Redirect::to('/');
        }

        $authors = $mod->authors;
        $versions = $mod->versions;
        $maintainers = $mod->maintainers;

        $input = Input::only('name', 'selected_versions', 'selected_authors', 'selected_maintainers', 'deck', 'website',
            'download_link', 'donate_link', 'wiki_link', 'description', 'slug', 'mod_list_hide');

        $messages = [
            'unique' => 'This mod already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input, [
            'name' => 'required|unique:mods,name,' . $mod->id,
            'selected_authors' => 'required',
            'selected_versions' => 'required',
            'deck' => 'required',
            'website' => 'url',
            'download_url' => 'url',
            'wiki_url' => 'url',
            'donate_link' => 'url',
        ], $messages);

        if ($validator->fails()) {
            return Redirect::to('/mod/edit/' . $mod->id)->withErrors($validator)->withInput();
        } else {
            $mod->name = $input['name'];
            $mod->deck = $input['deck'];
            $mod->website = $input['website'];
            $mod->download_link = $input['download_link'];
            $mod->donate_link = $input['donate_link'];
            $mod->wiki_link = $input['wiki_link'];
            $mod->description = $input['description'];

            if ($can_edit_maintainers) {
                if ($input['slug'] == '' || $input['slug'] == $mod->slug) {
                    $slug = Str::slug($input['name']);
                } else {
                    $slug = $input['slug'];
                }

                $mod->slug = $slug;

                if ($input['mod_list_hide'] == 1) {
                    $mod->mod_list_hide = 1;
                } else {
                    $mod->mod_list_hide = 0;
                }
            }

            $mod->last_ip = Request::getClientIp();

            $success = $mod->save();

            if ($success) {
                foreach ($authors as $a) {
                    $mod->authors()->detach($a->id);
                }
                $mod->authors()->attach($input['selected_authors']);

                foreach ($versions as $v) {
                    $mod->versions()->detach($v->id);
                }
                $mod->versions()->attach($input['selected_versions']);

                if ($can_edit_maintainers) {
                    foreach ($maintainers as $m) {
                        $mod->maintainers()->detach($m->id);
                    }
                    if ($input['selected_maintainers']) {
                        $mod->maintainers()->attach($input['selected_maintainers']);
                    }
                }

                $updated_mod = Mod::find($mod->id);

                foreach ($updated_mod->versions as $v) {
                    $selected_versions[] = $v->name;
                }

                foreach ($updated_mod->authors as $a) {
                    $selected_authors[] = $a->id;
                }

                foreach ($updated_mod->maintainers as $m) {
                    $selected_maintainers[] = $m->id;
                }

                Cache::tags('mods')->flush();
                Queue::push('BuildCache');

                return View::make('mods.edit', [
                    'title' => $title,
                    'mod' => $mod,
                    'chosen' => true,
                    'success' => true,
                    'selected_versions' => $selected_versions,
                    'selected_authors' => $selected_authors,
                    'selected_maintainers' => $selected_maintainers,
                    'versions' => $minecraft_versions,
                    'can_edit_maintainers' => $can_edit_maintainers,
                ]);
            } else {
                return Redirect::action('ModController@getEdit', [$mod->id])
                    ->withErrors(['message' => 'Unable to edit mod.'])->withInput();
            }

        }
    }
}