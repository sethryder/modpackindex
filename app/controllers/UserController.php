<?php

class UserController extends BaseController
{
    public function getIndex()
    {
        $this->getLogin();
    }

    public function getLogin()
    {
        if (Auth::check())
        {
            return Redirect::intended('/');
        }

        return View::Make('user.login');
    }

    public function getLogout()
    {
        if (Auth::check())
        {
            Auth::logout();
        }

        return Redirect::intended('/');
    }

    public function getRegister()
    {
        return View::Make('user.register');
    }

    public function postLogin()
    {
        if (Auth::check())
        {
            return Redirect::intended('/');
        }

        $input = Input::only('email', 'password', 'remember_me');
        $remember_me = false;

        if ($input['remember_me'])
        {
            $remember_me = true;
        }

        if (Auth::attempt(array('email' => $input['email'], 'password' => $input['password'], 'is_active' => 1), $remember_me))
        {
            return Redirect::intended('/');
        }
        else
        {
            return Redirect::to('/login')->withErrors(['Error' => 'Unable to login with provided information.'])->withInput();
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

    public function getProfile($id=null, $page=null)
    {

    }

    public function getEditProfile()
    {

    }

    public function postEditProfile()
    {

    }

    public function getVerify($confirmation)
    {
        if ($user = User::where('confirmation', '=', $confirmation)->first())
        {
            $user->is_confirmed = 1;
            $user->is_active = 1;
            $user->save();

            $confirm = true;
        }
        else
        {
            $confirm = false;
        }
        return View::make('user.confirm', ['confirmed' => $confirm]);
    }

    public function getUserPermissions($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $user = User::find($id);
        $permissions = $user->permissions;
        $available_permissions = Permission::all();
        $selected_permissions = [];

        foreach ($permissions as $p)
        {
            $selected_permissions[] = $p->id;
        }

        return View::make('user.edit_permissions', ['user' => $user, 'selected_permissions' => $selected_permissions,
                'available_permissions' => $available_permissions]);
    }

    public function postUserPermissions($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $input = Input::only('selected_permissions');

        $user = User::find($id);
        $permissions = $user->permissions;

        foreach ($permissions as $p)
        {
            $user->permissions()->detach($p->id);
        }

        $user->permissions()->attach($input['selected_permissions']);

        //update done, refresh information
        $user = User::find($id);
        $permissions = $user->permissions;
        $available_permissions = Permission::all();

        foreach ($permissions as $p)
        {
            $selected_permissions[] = $p->id;
        }

        Cache::tags('user-permissions')->flush();

        return View::make('user.edit_permissions', ['user' => $user, 'selected_permissions' => $selected_permissions,
            'available_permissions' => $available_permissions, 'success' => true]);
    }
}