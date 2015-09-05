<?php

class ServerTagController extends BaseController
{
    public function getTags()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $tags = ServerTag::all();

        $title = 'List Server Tags - ' . $this->site_name;

        return View::make('tags.server.list', ['tags' => $tags]);
    }

    public function getAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add A Server Tag - ' . $this->site_name;

        return View::make('tags.server.add', ['title' => $title]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add A Server Tag - ' . $this->site_name;

        $input = Input::only('name', 'deck', 'description', 'slug');

        $messages = [
            'unique' => 'This server tag already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:server_tags',
                'deck' => 'required',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::action('ServerTagController@getAdd')->withErrors($validator)->withInput();
        }

        $server_tag = new ServerTag;

        $server_tag->name = $input['name'];
        $server_tag->deck = $input['deck'];
        $server_tag->description = $input['description'];
        $server_tag->last_ip = Request::getClientIp();

        if ($input['slug'] == '') {
            $slug = Str::slug($input['name']);
        } else {
            $slug = $input['slug'];
        }

        $server_tag->slug = $slug;
        $success = $server_tag->save();

        if ($success) {
            //Cache::tags('modpacks')->flush();
            //Queue::push('BuildCache');
            return View::make('tags.server.add', ['title' => $title, 'success' => true]);
        }

        return Redirect::action('ServerTagController@getAdd')->withErrors(['message' => 'Unable to add modpack tag.'])->withInput();
    }

    public function getEdit($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Edit A Server Tag - ' . $this->site_name;

        $server_tag = ServerTag::find($id);

        return View::make('tags.server.edit', ['title' => $title, 'server_tag' => $server_tag]);

    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Edit A Server Code - ' . $this->site_name;

        $input = Input::only('name', 'deck', 'description', 'slug');

        $server_tag = ServerTag::find($id);

        $messages = [
            'unique' => 'This server tag already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:server_tags,name,' . $server_tag->id,
                'deck' => 'required',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::action('ServerTagController@getEdit', [$id])->withErrors($validator)->withInput();
        }

        $server_tag->name = $input['name'];
        $server_tag->deck = $input['deck'];
        $server_tag->description = $input['description'];
        $server_tag->last_ip = Request::getClientIp();

        if ($input['slug'] == '' || $input['slug'] == $server_tag->slug) {
            $slug = Str::slug($input['name']);
        } else {
            $slug = $input['slug'];
        }

        $server_tag->slug = $slug;
        $success = $server_tag->save();

        if ($success) {
            //Cache::tags('modpacks')->flush();
            //Queue::push('BuildCache');
            return View::make('tags.server.edit',
                ['title' => $title, 'success' => true, 'server_tag' => $server_tag]);
        }

        return Redirect::action('ServerTagController@getEdit', [$id])->withErrors(['message' => 'Unable to edit modpack code.'])->withInput();
    }
}