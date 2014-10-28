<?php

class UserController extends BaseController
{
    public function getIndex()
    {
        $this->getLogin();
    }

    public function getLogin()
    {
        return View::Make('user.login');
    }

    public function getRegister()
    {
        return View::Make('user.register');
    }

    public function postLogin()
    {
        $input = Input::only('email', 'password');

        if (Auth::attempt(array('email' => $input['email'], 'password' => $input['password'], 'is_active' => 1)))
        {
            echo 'You are logged in!';
        }
        else
        {
            echo 'Unable to login!';
        }
    }

    public function postRegister()
    {
        $input = Input::only('username', 'email', 'password', 'confirm_password', 'recaptcha_response_field');

        $validator = Validator::make($input,
            array(
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
                'recaptcha_response_field' => 'required|recaptcha',
            )
        );

        if ($validator->fails())
        {
            return Redirect::to('/register')->withErrors($validator)->withInput();
        }
        else
        {
            $user = new User;
            $confirmation = str_random(64);

            $user->username = $input['username'];
            $user->email = $input['email'];
            $user->password = Hash::make($input['password']);
            $user->is_active = 1;
            $user->register_ip = Request::getClientIp();
            $user->last_ip = Request::getClientIp();
            $user->confirmation =  $confirmation;

            if ($user->save())
            {
                Mail::send('emails.auth.confirmation', array('confirmation' => $confirmation), function($message) use ($input)
                {
                    $message->from('noreply@modpackindex.com', 'Modpack Index');
                    $message->to($input['email'], $input['username'])->subject('Confirmation for ' . $input['username']);
                });

                return View::make('user.register_success', ['email' => $user->email]);
            }
            else
            {
                return Redirect::to('/register')->withErrors(['Error' => 'Unable to create account'])->withInput();
            }
        }
    }
}