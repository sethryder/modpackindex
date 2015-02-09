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
            'website.url' => 'Please provide a valid link to the website/forum post.',
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

        if (!$input['email'])
        {
            $input['email'] = 'noreply@modpackindex.com';
        }

        if ($validator->fails())
        {
            return Redirect::to('/submit-modpack')->withErrors($validator)->withInput();
        }
        else
        {
            Mail::send('emails.submitmodpack', array('input' => $input), function ($message) use ($input) {
                $message->from('noreply@modpackindex.com', 'Modpack Index');
                $message->replyTo($input['email']);
                $message->to($input['contact_email'], 'Seth')->subject('Modpack Submission: ' . $input['name']);
            });

            return View::make('pages.submitmodpack', ['success' => true, 'title' => $title]);
        }
    }

    public function getSubmitVideo()
    {
        $title = 'Submit Video / Playlist - '. $this->site_name;
        $types = [
            'Let\'s Play' => 'Let\'s Play',
            'Spotlight' => 'Spotlight',
            'Guide' => 'Guide',
            'Other' => 'Other (Please specify in comments.)',
        ];

        return View::make('pages.submitvideo', ['title' => $title, 'types' => $types]);
    }

    public function postSubmitVideo()
    {
        $title = 'Submit Video / Playlist - '. $this->site_name;
        $types = [
            'Let\'s Play' => 'Let\'s Play',
            'Spotlight' => 'Spotlight',
            'Tutorial / Guide' => 'Tutorial / Guide',
            'Other' => 'Other (Please specify in comments.)',
        ];


        $input = Input::only('url', 'type', 'mod', 'modpack', 'email', 'comments', 'recaptcha_response_field');
        $input['contact_email'] = 'ryder.seth@gmail.com';
        $input['sender_ip'] = Request::getClientIp();

        $validator_error_messages = [
            'url.url' => 'Please provide a valid link to the Youtube video / playlist.',
            'url.required' => 'A link to the Youtube video / playlist is required.',
            'modpack.required_without_all' => 'You must select either a mod or a modpack.',
        ];


        $validator = Validator::make($input,
            array(
                'url' => 'required|url',
                'type' => 'required',
                'modpack' => 'required_without_all:mod',
                'email' => 'email',
                'recaptcha_response_field' => 'required|recaptcha',
            ),
            $validator_error_messages
        );

        if (!$input['email'])
        {
            $input['email'] = 'noreply@modpackindex.com';
        }

        if ($input['modpack'])
        {
            $email_subject = 'Video Submission: Modpack ' . $input['type'];
        }
        elseif ($input['mod'])
        {
            $email_subject = 'Video Submission: Mod ' . $input['type'];
        }

        if ($validator->fails())
        {
            return Redirect::to('/submit-video')->withErrors($validator)->withInput();
        }
        else
        {
            Mail::send('emails.submitvideo', array('input' => $input), function ($message) use ($input, $email_subject) {
                $message->from('noreply@modpackindex.com', 'Modpack Index');
                $message->replyTo($input['email']);
                $message->to($input['contact_email'], 'Seth')->subject($email_subject);
            });

            return View::make('pages.submitvideo', ['success' => true, 'title' => $title, 'types' => $types]);
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