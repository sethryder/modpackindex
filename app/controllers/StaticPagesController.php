<?php

class StaticPagesController extends BaseController
{
    public function getAbout()
    {
        $title = 'About - '. $this->site_name;
        return View::Make('pages.about', ['title' => $title]);
    }

    public function getContact()
    {
        $title = 'Contact Us - '. $this->site_name;

        return View::make('pages.contact', ['title' => $title]);
    }

    public function postContact()
    {
        $title = 'Contact Us - '. $this->site_name;

        $input = Input::only('name', 'email', 'message', 'recaptcha_response_field');
        $input['contact_email'] = 'ryder.seth@gmail.com';
        $input['sender_ip'] = Request::getClientIp();

        $validator = Validator::make($input,
            array(
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required',
                'recaptcha_response_field' => 'required|recaptcha',
            )
        );

        if ($validator->fails())
        {
            return Redirect::to('/contact')->withErrors($validator)->withInput();
        }
        else
        {
            Mail::send('emails.contact', array('input' => $input), function ($message) use ($input) {
                $message->from('noreply@modpackindex.com', 'Modpack Index');
                $message->replyTo($input['email'], $input['name']);
                $message->to($input['contact_email'], 'Seth')->subject('Contact Form from ' . $input['name']);
            });

            return View::make('pages.contact', ['success' => true, 'title' => $title]);
        }
    }

    public function getSubmitModpack()
    {
        $title = 'Submit Modpack - '. $this->site_name;

        return View::make('pages.submitmodpack', ['title' => $title]);
    }

    public function postSubmitModpack()
    {
        $title = 'Submit Modpack - '. $this->site_name;

        $input = Input::only('name', 'creators_name', 'minecraft_version', 'launcher', 'website', 'modlist', 'packcode',
            'deck', 'description', 'comments', 'email', 'recaptcha_response_field');
        $input['contact_email'] = 'ryder.seth@gmail.com';
        $input['sender_ip'] = Request::getClientIp();

        $validator_error_messages = [
            'name.required' => 'The Modpack name is required.',
            'creators_name.required' => 'The name of the Modpack Creator(s) is required.',
            'website.required' => 'The website/forum post is required.',
            'modlist.required' => 'A link to the mod list is required.',
            'modlist.url' => 'Please provide a valid link to the mod list.',
            'deck.required' => 'A short description for the Modpack is required.'
        ];


        $validator = Validator::make($input,
            array(
                'name' => 'required',
                'creators_name' => 'required',
                'minecraft_version' => 'required',
                'launcher' => 'required',
                'website' => 'required|url',
                'modlist' => 'required|url',
                'deck' => 'required',
                'email' => 'email',
                'recaptcha_response_field' => 'required|recaptcha',
            ),
            $validator_error_messages
        );

        if ($validator->fails())
        {
            return Redirect::to('/submit-modpack')->withErrors($validator)->withInput();
        }
        else
        {
            Mail::send('emails.submitmodpack', array('input' => $input), function ($message) use ($input) {
                $message->from('noreply@modpackindex.com', 'Modpack Index');
                $message->replyTo($input['email'], $input['name']);
                $message->to($input['contact_email'], 'Seth')->subject('Modpack Submission: ' . $input['name']);
            });

            return View::make('pages.submitmodpack', ['success' => true, 'title' => $title]);
        }
    }

    public function getPackCodes()
    {
        $title = 'What are Modpack Codes? - '. $this->site_name;

        return View::make('pages.packcodes', ['title' => $title]);
    }

    public function getNotLaunched()
    {
        return View::make('hello');
    }
}