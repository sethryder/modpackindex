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
}