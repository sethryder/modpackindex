<?php

class CreatorController extends BaseController
{
    public function getAdd()
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Modpack Creator - ' . $this->site_name;
        return View::make('creators.add', ['title' => $title]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Modpack Creator - ' . $this->site_name;

        $input = Input::only('name', 'deck', 'website', 'donate_link', 'bio', 'slug');

        $messages = [
            'unique' => 'The modpack creator already exists in the database.',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:creators,name',
                'website' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/creator/add/')->withErrors($validator)->withInput();
        }
        else
        {
            $creator = new Creator;

            $creator->name = $input['name'];
            $creator->deck = $input['deck'];
            $creator->website = $input['website'];
            $creator->donate_link = $input['donate_link'];
            $creator->bio = $input['bio'];

            if ($input['slug'] == '')
            {
                $slug = Str::slug($input['name']);
            }
            else
            {
                $slug = $input['slug'];
            }

            $creator->slug = $slug;
            $creator->last_ip = Request::getClientIp();

            $success = $creator->save();

            if ($success)
            {
                return View::make('creators.add', ['title' => $title, 'success' => true]);
            }
            else
            {
                return Redirect::to('/creator/add/')->withErrors(['message' => 'Unable to add modpack creator.'])->withInput();
            }

        }
    }

    public function getEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Edit A Modpack Creator - ' . $this->site_name;

        $creator = Creator::find($id);

        return View::make('creators.edit', ['title' => $title, 'creator' => $creator]);
    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Edit A Modpack Creator - ' . $this->site_name;
        $creator = Creator::find($id);

        $input = Input::only('name', 'deck', 'website', 'donate_link', 'bio', 'slug');

        $messages = [
            'unique' => 'The modpack creator already exists in the database.',
            'url' => 'The :attribute field is not a valid URL.'
        ];

        $validator = Validator::make($input,
            [
                'name' => 'required|unique:creators,name,'.$creator->id,
                'website' => 'url',
                'donate_link' => 'url',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/creator/add/')->withErrors($validator)->withInput();
        }
        else
        {
            $creator->name = $input['name'];
            $creator->deck = $input['deck'];
            $creator->website = $input['website'];
            $creator->donate_link = $input['donate_link'];
            $creator->bio = $input['bio'];

            if ($input['slug'] == '' || $input['slug'] == $creator->slug)
            {
                $slug = Str::slug($input['name']);
            }
            else
            {
                $slug = $input['slug'];
            }

            $creator->slug = $slug;
            $creator->last_ip = Request::getClientIp();

            $success = $creator->save();

            if ($success)
            {
                return View::make('creators.edit', ['title' => $title, 'creator' => $creator, 'success' => true]);
            }
            else
            {
                return Redirect::to('/creator/edit/'. $id)->withErrors(['message' => 'Unable to edit modpack creator.'])->withInput();
            }

        }

    }
}