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
                $message->from('noreply@modpackindex.com', 'Modpack Index Contact Form');
                $message->replyTo($input['email'], $input['name']);
                $message->to($input['contact_email'], 'Seth')->subject('Contact Form from ' . $input['name']);
            });

            return View::make('pages.contact', ['success' => true, 'title' => $title]);
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