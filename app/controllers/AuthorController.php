<?php

class AuthorController extends BaseController
{
    public function getAdd()
    {
        $title = 'Add An Author - ' . $this->site_name;
        return View::make('authors.add', ['title' => $title]);
    }

    public function postAdd()
    {
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

        if ($validator->fails())
        {
            return Redirect::to('/author/add/')->withErrors($validator)->withInput();
        }
        else
        {
            $author = new Author;

            $author->name = $input['name'];
            $author->deck = $input['deck'];
            $author->website = $input['website'];
            $author->donate_link = $input['donate_link'];
            $author->bio = $input['bio'];

            if ($input['slug'] == '')
            {
                $slug = Str::slug($input['name']);
            }
            else
            {
                $slug = $input['slug'];
            }

            $author->slug = $slug;
            $author->last_ip = Request::getClientIp();

            $success = $author->save();

            if ($success)
            {
                return View::make('authors.add', ['title' => $title, 'success' => true]);
            }
            else
            {
                return Redirect::to('/author/add/')->withErrors(['message' => 'Unable to add author.'])->withInput();
            }

        }
    }
}