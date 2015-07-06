<?php

class ImportController extends BaseController
{
    public function getStartImport()
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $mcf_mods_array = [];
        $nem_mods_array = [];

        $title = 'Import Mod - ' . $this->site_name;

        $raw_mcf_mods = ImportMCFModlist::orderBy('name', 'asc')->get();
        $raw_nem_mods = ImportNEM::orderBy('name', 'asc')->get();

        $mcf_mods_array[0] = 'None';
        $nem_mods_array[0] = 'None';

        foreach ($raw_mcf_mods as $mcf_mod) {
            $mcf_mod_id = $mcf_mod->id;
            $mcf_mods_array[$mcf_mod_id] = $mcf_mod->name;
        }

        foreach ($raw_nem_mods as $nem_mod) {
            $nem_mod_id = $nem_mod->id;
            $nem_mods_array[$nem_mod_id] = $nem_mod->name;
        }

        return View::make('imports.import', [
            'title' => $title,
            'mcf_mods_array' => $mcf_mods_array,
            'nem_mods_array' => $nem_mods_array
        ]);
    }

    public function postStartImport()
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $mod_info_array = [];
        $input = Input::only('import_nem', 'import_mcf', 'url', 'json');

        if ($input['import_nem']) {
            $import_type = 'nem';
            $nem_mod = ImportNEM::find($input['import_nem']);
            $mod_info = true;
        } elseif ($input['import_mcf']) {
            $import_type = 'mcf';
            $mcf_mod = ImportMCFModlist::find($input['import_mcf']);
            $mod_info = true;
        } elseif (Input::hasFile('import_file')) {
            $import_type = 'mcmodinfo';

            $import_file = Input::file('import_file');
            $mod_info = $this->processUpload($import_file);

            if (!$mod_info) {
                return Redirect::to('/mod/import')->withErrors(['message' => 'Unable to process uploaded file.'])->withInput();
            }
        } else {
            $import_type = 'mcmodinfo';

            if ($input['url']) {
                $import = new Import;
                $mod_info = $import->downloadModInfo($input['url']);

                if (!$mod_info) {
                    return Redirect::to('/mod/import')->withErrors(['message' => 'Cannot parse URL contents.'])->withInput();
                }
            } elseif ($input['json']) {
                $mod_info = json_decode($input['json']);

                if (!$mod_info) {
                    return Redirect::to('/mod/import')->withErrors(['message' => 'Cannot parse JSON.'])->withInput();
                }
            }
        }

        if ($import_type == 'mcmodinfo') {
            $mod_info_array['name'] = $mod_info[0]->name;
            $mod_info_array['description'] = (isset($mod_info[0]->description) ? $mod_info[0]->description : '');
            $mod_info_array['minecraft_version'] = (isset($mod_info[0]->mcversion) ? serialize([$mod_info[0]->mcversion]) : serialize(['']));
            $mod_info_array['url'] = (isset($mod_info[0]->url) ? $mod_info[0]->url : '');

            if (isset($mod_info[0]->authorList)) {
                $mod_info_array['authors'] = serialize($mod_info[0]->authorList);
            }

            if (isset($mod_info[0]->authors)) {
                $mod_info_array['authors'] = serialize($mod_info[0]->authors);
            }

        } elseif ($import_type == 'nem') {
            $mod_info_array['description'] = '';

            //NEM doesn't include any spaces in the name. So we are going to check MFC to see if they list it with a space.
            $name_with_spaces = preg_replace('/(?<!\ )[A-Z0-9]/', ' $0', $nem_mod->name);
            $mcf_result = ImportMCFModlist::where('name', ltrim($name_with_spaces))->first();

            if (isset($mcf_result->name)) {
                $mod_info_array['name'] = $mcf_result->name;
                $mod_info_array['description'] = $mcf_result->description;
            } else {
                $mod_info_array['name'] = $nem_mod->name;
                $mcf_result = ImportMCFModlist::where('name', $nem_mod->name)->first();

                if (isset($mcf_result->description)) {
                    $mod_info_array['description'] = $mcf_result->description;
                }
            }


            $mod_info_array['minecraft_version'] = $nem_mod->raw_minecraft_versions;
            $mod_info_array['authors'] = $nem_mod->raw_authors;
            $mod_info_array['url'] = $nem_mod->url;
        } elseif ($import_type == 'mcf') {
            $mod_info_array['name'] = $mcf_mod->name;
            $mod_info_array['description'] = $mcf_mod->description;
            $mod_info_array['minecraft_version'] = $mcf_mod->raw_minecraft_versions;
            $mod_info_array['authors'] = $mcf_mod->raw_authors;

            if (preg_match('/j\.mp/', $mcf_mod->url)) {
                $mod_info_array['url'] = $this->getBitlyFullURL($mcf_mod->url);
            } else {
                $mod_info_array['url'] = $mcf_mod->url;
            }
        }

        if ($mod_info) {
            $import_index = new Import;

            $import_index->name = $mod_info_array['name'];
            $import_index->description = $mod_info_array['description'];
            $import_index->minecraft_version = $mod_info_array['minecraft_version'];
            $import_index->url = $mod_info_array['url'];
            $import_index->raw_authors = $mod_info_array['authors'];
            $import_index->type = 1;

            $success = $import_index->save();

            if (!$success) {
                return Redirect::to('/mod/import')->withErrors(['message' => 'Unable to save to database.'])->withInput();
            }

            $import_id = $import_index->id;

            $authors_to_process = $this->processAuthors($import_id, unserialize($mod_info_array['authors']));

            if ($authors_to_process > 0) {
                return Redirect::to('/mod/import/' . $import_id . '/author');
            } else {
                return Redirect::to('/mod/import/' . $import_id);
            }
        }

        return Redirect::to('/mod/import')->withErrors(['message' => 'Something went wrong.'])->withInput();
    }

    public function getImportAuthor($import_id, $author_id = null)
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $import_mod = Import::find($import_id);

        if ($author_id) {
            $import_author = ImportAuthor::where('import_id', '=', $import_id)->where('status', '=', 0)->where('id',
                '=', $author_id)->first();
        } else {
            $import_author = ImportAuthor::where('import_id', '=', $import_id)->where('status', '=', 0)->first();
        }


        if (!$import_author) {
            return Redirect::to('/mod/import/' . $import_id);
        }

        $title = 'Author Import for ' . $import_mod->name . ' - ' . $this->site_name;

        $to_process_count = count($import_author);

        return View::make('imports.author', [
            'title' => $title,
            'import_mod' => $import_mod,
            'author' => $import_author,
            'to_process_count' => $to_process_count,
            'chosen' => true
        ]);

    }

    public function postImportAuthor($import_id, $author_id)
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $import_mod = Import::find($import_id);
        $import_author = ImportAuthor::find($author_id);

        $input = Input::only('alias', 'name', 'deck', 'website', 'donate_link', 'bio', 'slug');

        $messages = [
            'unique' => 'The author already exists in the database.',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:authors,name',
                'website' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::to('/mod/import/' . $import_id . '/' . $author_id)->withErrors($validator)->withInput();
        } else {
            if ($input['alias'] == 0) {
                $author = new Author;

                $author->name = $input['name'];
                $author->deck = $input['deck'];
                $author->website = $input['website'];
                $author->donate_link = $input['donate_link'];
                $author->bio = $input['bio'];

                if ($input['slug'] == '') {
                    $slug = Str::slug($input['name']);
                } else {
                    $slug = $input['slug'];
                }

                $author->slug = $slug;
                $author->last_ip = Request::getClientIp();

                $author_import_status = 1;

                $success = $author->save();
            } else {
                $alias = new AuthorAlias;

                $alias->author_id = $input['alias'];
                $alias->alias = $import_author->name;
                $author_import_status = 2;
                $success = $alias->save();

                $alias_id = $alias->id;
            }

            if ($success) {
                $import_author->status = $author_import_status;

                if (isset($alias_id)) {
                    $import_author->author_alias_id = $alias_id;
                }

                $import_author->save();

                $import_authors = ImportAuthor::where('import_id', '=', $import_id)->where('status', '=', 0)->first();

                if (!$import_authors) {
                    return Redirect::to('/mod/import/' . $import_mod->id);
                } else {
                    return Redirect::to('/mod/import/' . $import_mod->id . '/author');
                }
            } else {
                return Redirect::to('/mod/import/' . $import_mod->id . '/authors/' . $import_mod->id)
                    ->withErrors(['message' => 'Unable to add author.'])->withInput();
            }

        }
    }

    public function getImportMod($import_id)
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $import_mod = Import::find($import_id);
        $selected_authors = [];
        $selected_versions = [];
        $form_mod = [];
        $to_process_authors = ImportAuthor::where('import_id', '=', $import_id)->where('status', '=', 0)->first();
        $versions = MinecraftVersion::all();

        if ($to_process_authors) {
            return Redirect::to('/mod/import/' . $import_id . '/author');
        }

        $import_authors = ImportAuthor::where('import_id', '=', $import_id)->get();

        foreach ($import_authors as $author) {
            if ($author->status == 1) {
                $db_author = Author::where('name', 'LIKE', $author->name)->first();
                $selected_authors[] = $db_author->id;
            } elseif ($author->status == 2) {
                $db_alias = AuthorAlias::where('id', '=', $author->author_alias_id)->first();
                $selected_authors[] = $db_alias->author_id;
            }
        }

        foreach (unserialize($import_mod['minecraft_version']) as $mod_version) {
            $selected_versions[] = $mod_version;
        }

        $form_mod['name'] = $import_mod->name;
        $form_mod['deck'] = $import_mod->description;
        $form_mod['minecraft_version'] = $import_mod->minecraft_version;
        $form_mod['website'] = $import_mod->url;

        $title = 'Import Mod ' . $import_mod->name . ' - ' . $this->site_name;

        return View::make('imports.mod', [
            'title' => $title,
            'form_mod' => $form_mod,
            'import_mod' => $import_mod,
            'selected_authors' => $selected_authors,
            'selected_versions' => $selected_versions,
            'versions' => $versions,
            'chosen' => true
        ]);
    }

    public function postImportMod($import_id)
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $import_mod = Import::find($import_id);
        $versions = MinecraftVersion::all();
        $title = 'Import A Mod - ' . $this->site_name;

        $input = Input::only('name', 'selected_versions', 'selected_authors', 'deck', 'website', 'download_link',
            'donate_link', 'wiki_link', 'description', 'slug', 'mod_list_hide');

        $messages = [
            'unique' => 'This mod already exists in the database. If it requires an update let us know!',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:mods,name',
                'selected_authors' => 'required',
                'versions' => 'selected_versions',
                'deck' => 'required',
                'website' => 'url',
                'download_url' => 'url',
                'wiki_url' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::to('/mod/import/' . $import_mod->id)->withErrors($validator)->withInput();
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
                foreach ($input['selected_authors'] as $author) {
                    $mod->authors()->attach($author);
                }

                foreach ($input['selected_versions'] as $version) {
                    $mod->versions()->attach($version);
                }

                Cache::tags('mods')->flush();
                Queue::push('BuildCache');

                $import_mod->status = 1;
                $import_mod->save();

                $raw_mcf_mods = ImportMCFModlist::orderBy('name', 'asc')->get();
                $raw_nem_mods = ImportNEM::orderBy('name', 'asc')->get();

                $mcf_mods_array[0] = 'None';
                $nem_mods_array[0] = 'None';

                foreach ($raw_mcf_mods as $mcf_mod) {
                    $mcf_mod_id = $mcf_mod->id;
                    $mcf_mods_array[$mcf_mod_id] = $mcf_mod->name;
                }

                foreach ($raw_nem_mods as $nem_mod) {
                    $nem_mod_id = $nem_mod->id;
                    $nem_mods_array[$nem_mod_id] = $nem_mod->name;
                }

                return View::make('imports.import', [
                    'title' => $title,
                    'chosen' => true,
                    'success' => true,
                    'mcf_mods_array' => $mcf_mods_array,
                    'nem_mods_array' => $nem_mods_array,
                    'versions' => $versions
                ]);
            } else {
                return Redirect::to('/mod/import/' . $import_mod->id)->withErrors(['message' => 'Unable to import mod.'])->withInput();
            }

        }
    }

    private function processAuthors($import_id, $authors)
    {
        foreach ($authors as $author) {
            $result = Author::where('name', 'LIKE', $author)->first();

            $import_author = new ImportAuthor;
            $import_author->name = $author;
            $import_author->import_id = $import_id;

            if (!$result) {
                $alias = AuthorAlias::where('alias', 'LIKE', $author)->first();

                if ($alias) {
                    $import_author->author_alias_id = $alias->id;
                    $import_author->status = 2;
                } else {
                    $import_author->status = 0;
                }
            } else {
                $import_author->status = 1;
            }

            $import_author->save();
        }

        $to_process_count = ImportAuthor::where('import_id', '=', $import_id)->where('status', '=', 0)->count();

        return $to_process_count;
    }

    private function processUpload($import_file)
    {
        $mod_info = false;
        $mod_info_array = [];

        $valid_mime_types = [
            'application/java-archive',
            'text/plain',
            'application/zip',
        ];

        $mime_type = $import_file->getMimeType();

        if (!in_array($mime_type, $valid_mime_types)) {
            return false;
        }

        if ($mime_type == 'application/java-archive' || $mime_type == 'application/zip') {
            $temp_file_path = '/tmp/mcmodinfo_' . time();
            mkdir($temp_file_path);

            exec('unzip ' . $import_file->getRealPath() . ' mcmod.info -d ' . $temp_file_path);
            $raw_file_contents = file_get_contents($temp_file_path . '/mcmod.info');

            unlink($temp_file_path . '/mcmod.info');
            rmdir($temp_file_path);

            if (!$raw_file_contents) {
                return false;
            }

            $mod_info = json_decode($raw_file_contents);

            if (!$mod_info) {
                return false;
            }
        }

        if ($mime_type == 'text/plain') {
            $raw_file_contents = file_get_contents($import_file->getRealPath());
            $mod_info = json_decode($raw_file_contents);

            if (!$mod_info) {
                return false;
            }
        }

        if (isset($mod_info->modList)) {
            $mod_info_array[0] = $mod_info->modList[0];
        } else {
            $mod_info_array = $mod_info;
        }

        return $mod_info_array;
    }

    private function getBitlyFullURL($short_url)
    {
        $api_url = Config::get('services.bitly.url');
        $oath_token = Config::get('services.bitly.token');

        $query_url = $api_url . 'v3/expand?access_token=' . $oath_token . '&shortUrl=' . $short_url;

        $client = new \GuzzleHttp\Client();
        $response = $client->get($query_url);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $raw_body = $response->getBody();
        $decoded_body = json_decode($raw_body);


        if (!$decoded_body) {
            return false;
        } else {
            $long_url = $decoded_body->data->expand[0]->long_url;

            return $long_url;
        }
    }
}