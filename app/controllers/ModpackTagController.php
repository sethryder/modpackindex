<?php

class ModpackTagController extends BaseController
{
    public function getModpackTag($slug)
    {

    }

    public function getAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add A Modpack Tag - ' . $this->site_name;

        return View::make('tags.modpacks.add', ['title' => $title]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add A Modpack Tag - ' . $this->site_name;

        $input = Input::only('name', 'deck', 'description', 'slug');

        $messages = [
            'unique' => 'This modpack tag already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:modpack_tags',
                'deck' => 'required',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::action('ModpackTagController@getAdd')->withErrors($validator)->withInput();
        } else {
            $modpacktag = new ModpackTag;

            $modpacktag->name = $input['name'];
            $modpacktag->deck = $input['deck'];
            $modpacktag->description = $input['description'];
            $modpacktag->last_ip = Request::getClientIp();

            if ($input['slug'] == '') {
                $slug = Str::slug($input['name']);
            } else {
                $slug = $input['slug'];
            }

            $modpacktag->slug = $slug;
            $success = $modpacktag->save();

            if ($success) {
                Cache::tags('modpacks')->flush();
                Queue::push('BuildCache');

                return View::make('tags.modpacks.add', ['title' => $title, 'success' => true]);
            } else {
                return Redirect::action('ModpackTagController@getAdd')->withErrors(['message' => 'Unable to add modpack tag.'])->withInput();
            }

        }
    }

    public function getEdit($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Edit A Modpack Tag - ' . $this->site_name;

        $modpacktag = ModpackTag::find($id);

        return View::make('tags.modpacks.edit', ['title' => $title, 'modpacktag' => $modpacktag]);

    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Edit A Modpack Code - ' . $this->site_name;

        $input = Input::only('name', 'deck', 'description', 'slug');

        $modpacktag = ModpackTag::find($id);

        $messages = [
            'unique' => 'This modpack tag already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:modpack_tags,name,' . $modpacktag->id,
                'deck' => 'required',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::action('ModpackTagController@getEdit', [$id])->withErrors($validator)->withInput();
        } else {
            $modpacktag->name = $input['name'];
            $modpacktag->deck = $input['deck'];
            $modpacktag->description = $input['description'];
            $modpacktag->last_ip = Request::getClientIp();

            if ($input['slug'] == '' || $input['slug'] == $modpacktag->slug) {
                $slug = Str::slug($input['name']);
            } else {
                $slug = $input['slug'];
            }

            $modpacktag->slug = $slug;
            $success = $modpacktag->save();

            if ($success) {
                Cache::tags('modpacks')->flush();
                Queue::push('BuildCache');

                return View::make('tags.modpacks.edit',
                    ['title' => $title, 'success' => true, 'modpacktag' => $modpacktag]);
            } else {
                return Redirect::action('ModpackTagController@getEdit', [$id])->withErrors(['message' => 'Unable to edit modpack code.'])->withInput();
            }

        }
    }
}