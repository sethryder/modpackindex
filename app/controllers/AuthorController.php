<?php

class AuthorController extends BaseController
{
    public function getAuthor($slug)
    {

    }

    public function getAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add An Author - ' . $this->site_name;

        return View::make('authors.add', ['title' => $title]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add An Author - ' . $this->site_name;

        $input = Input::only('name', 'deck', 'website', 'donate_link', 'bio', 'slug');

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
            return Redirect::action('AuthorController@getAdd')->withErrors($validator)->withInput();
        }

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

        $success = $author->save();

        if ($success) {
            return View::make('authors.add', ['title' => $title, 'success' => true]);
        }

        return Redirect::action('AuthorController@getAdd')->withErrors(['message' => 'Unable to add author.'])->withInput();
    }

    public function getEdit($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Edit An Author - ' . $this->site_name;

        $author = Author::find($id);

        return View::make('authors.edit', ['title' => $title, 'author' => $author]);
    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Edit An Author - ' . $this->site_name;
        $author = Author::find($id);

        $input = Input::only('name', 'deck', 'website', 'donate_link', 'bio', 'slug');

        $messages = [
            'unique' => 'The author already exists in the database.',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:authors,name,' . $author->id,
                'website' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::action('AuthorController@getEdit', [$id])->withErrors($validator)->withInput();
        }

        $author->name = $input['name'];
        $author->deck = $input['deck'];
        $author->website = $input['website'];
        $author->donate_link = $input['donate_link'];
        $author->bio = $input['bio'];

        if ($input['slug'] == '' || $input['slug'] == $author->slug) {
            $slug = Str::slug($input['name']);
        } else {
            $slug = $input['slug'];
        }

        $author->slug = $slug;
        $author->last_ip = Request::getClientIp();

        $success = $author->save();

        if ($success) {
            return View::make('authors.edit', ['title' => $title, 'success' => true, 'author' => $author]);
        }

        return Redirect::action('AuthorController@getEdit', [$id])->withErrors(['message' => 'Unable to add author.'])->withInput();
    }
}