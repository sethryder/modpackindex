<?php

class UserController extends BaseController
{
    public function getIndex()
    {
        $this->getLogin();
    }

    public function getLogin()
    {
        if (Auth::check()) {
            return Redirect::intended('/');
        }

        $use_captcha = false;
        $ip_cache_key = 'login-attempts-' . md5(Request::getClientIp());

        if (Cache::has($ip_cache_key)) {
            $attempts = Cache::get($ip_cache_key);

            if ($attempts >= 5) {
                $use_captcha = true;
            }
        }

        $title = 'Login - ' . $this->site_name;

        return View::Make('user.login', ['title' => $title, 'use_captcha' => $use_captcha]);
    }

    public function getLogout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return Redirect::intended('/');
    }

    public function getRegister()
    {
        $title = 'Register - ' . $this->site_name;

        return View::Make('user.register', ['title' => $title]);
    }

    public function getForgotPassword()
    {
        $title = 'Forgot Password - ' . $this->site_name;

        return View::Make('user.forgot_password', ['title' => $title]);
    }

    public function postForgotPassword()
    {
        $one_hour_ago = Carbon\Carbon::now()->subHours(1)->toDateTimeString();
        //clean up old records
        //PasswordReminder::where('created_at', '<=', time() - (60))->delete();

        $input = Input::only('email', 'g-recaptcha-response');

        $validator_error_messages = [
            'g-recaptcha-response.required' => 'reCAPTCHA verification is required.',
        ];

        $validator = Validator::make($input,
            array(
                'email' => 'required|email',
                'g-recaptcha-response' => 'required|recaptcha',
            ),
            $validator_error_messages
        );

        if ($validator->fails()) {
            return Redirect::to('/forgot')->withErrors($validator);
        } else {
            //check to see if valid user
            $user = User::where('email', $input['email'])->first();

            if (!$user) {
                return Redirect::to('/forgot')->withErrors(['Error' => 'Email does not exist in our database.']);
            }

            //make sure no password reminder is already out there.
            $existing_reminders = PasswordReminder::where('created_at', '>=', $one_hour_ago)->
            where('email', $input['email'])->count();

            if ($existing_reminders > 0) {
                return Redirect::to('/forgot')->withErrors(['Error' => 'A password reminder for this email is already active.']);
            }

            //make sure this IP isn't spamming password requests
            $ip_reminder_count = PasswordReminder::where('created_at', '>=', $one_hour_ago)->
            where('ip', Request::getClientIp())->count();

            if ($ip_reminder_count > 0) {
                return Redirect::to('/forgot')->withErrors(['Error' => 'A password reminder has already been requested with your IP address.']);
            }

            $password_reset = new PasswordReminder;
            $token = str_random(64);

            $password_reset->email = $input['email'];
            $password_reset->token = $token;
            $password_reset->ip = Request::getClientIp();

            $success = $password_reset->save();

            if ($success) {
                Mail::send('emails.auth.password_reset', array('token' => $token), function ($message) use ($user) {
                    $message->from('noreply@modpackindex.com', 'Modpack Index');
                    $message->to($user->email, $user->username)->subject('Password Reset for ' . $user->username);
                });

                return View::make('user.forgot_password', ['success' => true]);
            } else {
                return Redirect::to('/reset')->withErrors(['Error' => 'Unable to reset password.']);
            }
        }

    }

    public function getResetPassword($token = null)
    {
        $title = 'Reset Password - ' . $this->site_name;

        $one_hour_ago = Carbon\Carbon::now()->subHours(1)->toDateTimeString();

        if (!$token) {
            return Redirect::to('/');
        }

        $reset_request = PasswordReminder::where('created_at', '>=', $one_hour_ago)->
        where('token', $token)->first();

        if (!$reset_request) {
            return Redirect::to('/');
        }

        $user = User::where('email', $reset_request->email)->first();

        return View::Make('user.reset_password', ['title' => $title, 'token' => $token, 'username' => $user->username]);
    }

    public function postResetPassword($token)
    {
        $one_hour_ago = Carbon\Carbon::now()->subHours(1)->toDateTimeString();

        $title = 'Reset Password - ' . $this->site_name;

        $input = Input::only('new_password', 'confirm_password', 'token');

        $reset_request = PasswordReminder::where('created_at', '>=', $one_hour_ago)->
        where('token', $token)->first();

        if (!$reset_request) {
            return Redirect::to('/');
        }

        $validator = Validator::make($input,
            [
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password',
            ]);

        if ($validator->fails()) {
            return Redirect::to('/reset/' . $token)->withErrors($validator);
        } else {
            $user = User::where('email', $reset_request->email)->first();

            $user->password = Hash::make($input['new_password']);
            $user->remember_token = null; //kill the remember_token for security
            $user->last_ip = Request::getClientIp();

            $success = $user->save();

            if ($success) {
                return View::make('user.reset_password', ['title' => $title, 'success' => true, 'user' => $user]);
            } else {
                return Redirect::to('/reset/' . $token)->withErrors(['message' => 'Unable to reset password.']);
            }
        }
    }

    public function postLogin()
    {
        if (Auth::check()) {
            return Redirect::intended('/');
        }

        $attempts = 0;
        $use_captcha = false;

        $ip_cache_key = 'login-attempts-' . md5(Request::getClientIp());

        if (Cache::has($ip_cache_key)) {
            $attempts = Cache::get($ip_cache_key);

            if ($attempts >= 5) {
                $use_captcha = true;
            }
        }

        $input = Input::only('email', 'password', 'remember_me', 'g-recaptcha-response');

        if ($use_captcha) {
            $validator_error_messages = [
                'g-recaptcha-response.required' => 'reCAPTCHA verification is required.',
            ];
            $validator = Validator::make($input,
                [
                    'g-recaptcha-response' => 'required|recaptcha',
                ],
                $validator_error_messages
            );

            if ($validator->fails()) {
                return Redirect::to('/login')->withErrors($validator);
            }
        }

        $remember_me = false;

        if ($input['remember_me']) {
            $remember_me = true;
        }

        if (Auth::attempt(array(
            'email' => $input['email'],
            'password' => $input['password'],
            'is_active' => 1,
            'is_confirmed' => 1
        ), $remember_me)
        ) {
            return Redirect::intended('/');
        } else {
            Cache::put($ip_cache_key, $attempts + 1, 60);
            return Redirect::to('/login')->withErrors(['Error' => 'Unable to login with provided information.'])->withInput();
        }
    }

    public function postRegister()
    {
        $input = Input::only('username', 'email', 'password', 'confirm_password', 'g-recaptcha-response');

        $validator_error_messages = [
            'g-recaptcha-response.required' => 'reCAPTCHA verification is required.',
        ];

        $validator = Validator::make($input,
            array(
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
                'g-recaptcha-response' => 'required|recaptcha',
            ),
            $validator_error_messages
        );

        if ($validator->fails()) {
            return Redirect::to('/register')->withErrors($validator)->withInput();
        } else {
            $user = new User;
            $confirmation = str_random(64);

            $user->username = $input['username'];
            $user->email = $input['email'];
            $user->password = Hash::make($input['password']);
            $user->is_active = 1;
            $user->register_ip = Request::getClientIp();
            $user->last_ip = Request::getClientIp();
            $user->confirmation = $confirmation;

            if ($user->save()) {
                $user_info = new UserInfo;

                $user_info->user_id = $user->id;
                $user->last_ip = Request::getClientIp();
                $user_info->save();

                Mail::send('emails.auth.confirmation', array('confirmation' => $confirmation),
                    function ($message) use ($input) {
                        $message->from('noreply@modpackindex.com', 'Modpack Index');
                        $message->to($input['email'],
                            $input['username'])->subject('Confirmation for ' . $input['username']);
                    });

                return View::make('user.register_success', ['email' => $user->email]);
            } else {
                return Redirect::to('/register')->withErrors(['Error' => 'Unable to create account'])->withInput();
            }
        }
    }

    public function getProfile($username = null)
    {
        $my_profile = false;
        $show_modpacks = false;
        $show_mods = false;

        if (!$username) {
            if (!Auth::check()) {
                return Redirect::intended('/');
            } else {
                $username = Auth::user()->username;
                return Redirect::intended('/profile/' . $username);
            }
        } else {
            $raw_user = User::where('username', $username)->first();

            if (!$raw_user) {
                return Redirect::intended('/');
            }

            $raw_user_info = UserInfo::where('user_id', $raw_user->id)->first();

            $user_info = [
                'username' => $raw_user->username,
                'email' => $raw_user->email,
                'hide_email' => $raw_user->hide_email,
            ];

            $this->setProfileOptions($user_info['username']);
        }

        $user_info['real_name'] = $raw_user_info->real_name;
        $user_info['location'] = $raw_user_info->location;
        $user_info['website'] = $raw_user_info->website;
        $user_info['github'] = $raw_user_info->github;
        $user_info['about_me'] = $raw_user_info->about_me;

        $title = $user_info['username'] . '\'s Profile - ' . $this->site_name;

        return View::make('user.profile', [
            'user' => $user_info,
            'user_info' => $user_info,
            'title' => $title,
        ]);
    }

    public function getModpacks($username = null)
    {
        $modpacks_array = [];

        $this->setProfileOptions($username);

        $user = User::where('username', $username)->first();

        if (!$this->isThisMyUsername($username)) {
            if ($user->hide_mods_modpacks)
                return Redirect::intended('/profile/' . $user->username);
        }

        $title = $user['username'] . '\'s Modpacks - ' . $this->site_name;

        $modpacks = $user->modpacks;

        foreach ($modpacks as $modpack) {
            $raw_version = MinecraftVersion::find($modpack->minecraft_version_id);
            $version_slug = preg_replace('/\./', '-', $raw_version->name);

            $modpacks_array[] = [
                'id' => $modpack->id,
                'name' => $modpack->name,
                'slug' => $modpack->slug,
                'version' => $raw_version->name,
                'version_slug' => $version_slug,
            ];
        }

        return View::make('user.modpacks', [
            'title' => $title,
            'modpacks' => $modpacks_array,
            'user' => $user,
        ]);
    }

    public function getMods($username = null)
    {
        $mods_array = [];
        $version_array = [];
        $supported_versions = '';

        $this->setProfileOptions($username);

        $user = User::where('username', $username)->first();

        if (!$this->isThisMyUsername($username)) {
            if ($user->hide_mods_modpacks)
                return Redirect::intended('/profile/' . $user->username);
        }

        $title = $user['username'] . '\'s Modpacks - ' . $this->site_name;

        $mods = $user->mods;

        foreach ($mods as $mod) {
            $raw_versions = $mod->versions;

            foreach ($mod->versions as $v) {
                if (!in_array($v->name, $version_array)) {
                    $version_array[] = $v->name;
                    $supported_versions .= $v->name;
                    $supported_versions .= ', ';
                }
            }

            if (!$supported_versions) {
                $supported_versions = 'Unknown';
            }

            $mods_array[] = [
                'id' => $mod->id,
                'name' => $mod->name,
                'slug' => $mod->slug,
                'versions' => rtrim($supported_versions, ', '),
            ];
        }

        return View::make('user.mods', [
            'title' => $title,
            'mods' => $mods_array,
            'user' => $user,
        ]);

    }

    public function getEditProfile()
    {
        if (!Auth::check()) {
            return Redirect::intended('/');
        }

        $user = Auth::user();
        $user_info = UserInfo::where('user_id', $user->id)->first();

        $user_info->email = $user->email;
        $user_info->hide_email = $user->hide_email;
        $user_info->hide_mods_modpacks = $user->hide_mods_modpacks;

        $this->setProfileOptions($user->username);

        $title = 'Edit Profile - ' . $this->site_name;

        return View::make('user.edit', ['user' => $user, 'user_info' => $user_info, 'title' => $title]);

    }

    public function postEditProfile()
    {
        if (!Auth::check()) {
            return Redirect::intended('/');
        }

        $user = Auth::user();
        $user_info = UserInfo::where('user_id', $user->id)->first();

        $this->setProfileOptions($user->username);

        $title = 'Edit Profile - ' . $this->site_name;

        $input = Input::only('email', 'real_name', 'location', 'website', 'github', 'about_me', 'hide_email',
            'hide_mods_modpacks');

        $messages = [
            'url' => 'The :attribute field is not a valid URL.',
            'email' => 'You must provide a valid email address.',
        ];

        $validator = Validator::make($input,
            [
                'email' => 'required|max:255|email',
                'real_name' => 'max:255',
                'location' => 'max:255',
                'website' => 'url',
                'github' => 'max:39',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::to('/profile/edit')->withErrors($validator)->withInput();
        } else {
            $user->email = $input['email'];
            $user_info->real_name = $input['real_name'];
            $user_info->location = $input['location'];
            $user_info->website = $input['website'];
            $user_info->github = $input['github'];
            $user_info->about_me = $input['about_me'];

            if ($input['hide_email'] == 1) {
                $user->hide_email = 1;
            } else {
                $user->hide_email = 0;
            }

            if ($input['hide_mods_modpacks'] == 1) {
                $user->hide_mods_modpacks = 1;
            } else {
                $user->hide_mods_modpacks = 0;
            }

            $user_info->last_ip = Request::getClientIp();

            $user->save();
            $success = $user_info->save();

            $user_info->email = $user->email;
            $user_info->hide_email = $user->hide_email;

            if ($success) {
                return View::make('user.edit',
                    ['title' => $title, 'success' => true, 'user' => $user, 'user_info' => $user_info]);
            } else {
                return Redirect::to('/profile/edit')->withErrors(['message' => 'Unable to edit profile.'])->withInput();
            }
        }
    }

    public function getEditPassword()
    {
        if (!Auth::check()) {
            return Redirect::intended('/');
        }

        $user = Auth::user();

        $this->setProfileOptions($user->username);

        $title = 'Change Password - ' . $this->site_name;

        return View::make('user.edit_password', ['user' => $user, 'title' => $title]);

    }

    public function postEditPassword()
    {
        Validator::extend('passcheck', function ($attribute, $value, $parameters) {
            return Hash::check($value, Auth::user()->getAuthPassword());
        });

        if (!Auth::check()) {
            return Redirect::intended('/');
        }

        $user = Auth::user();

        $this->setProfileOptions($user->username);

        $title = 'Change Password - ' . $this->site_name;

        $input = Input::only('current_password', 'new_password', 'confirm_password');

        $messages = [
            'passcheck' => 'Invalid current password provided, please try again.'
        ];

        $validator = Validator::make($input,
            [
                'current_password' => 'required|passcheck',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::to('/profile/edit/password')->withErrors($validator);
        } else {
            $user->password = Hash::make($input['new_password']);
            $user->remember_token = null; //kill the remember_token for security
            $user->last_ip = Request::getClientIp();

            $success = $user->save();

            if ($success) {
                return View::make('user.edit_password', ['title' => $title, 'success' => true, 'user' => $user]);
            } else {
                return Redirect::to('/profile/edit/password')->withErrors(['message' => 'Unable to edit password.']);
            }
        }
    }

    public function getVerify($confirmation)
    {
        $error = false;

        if ($user = User::where('confirmation', '=', $confirmation)->first()) {
            if ($user->is_confirmed) {
                $confirm = true;
                $error = 'Your account has already been activated.';
            } else {
                $user->is_confirmed = 1;
                $user->is_active = 1;
                $user->save();

                $confirm = true;
            }
        } else {
            $confirm = false;
        }

        return View::make('user.confirm', ['confirmed' => $confirm, 'error' => $error]);
    }

    public function getUserPermissions($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $user = User::find($id);
        $permissions = $user->permissions;
        $available_permissions = Permission::all();
        $selected_permissions = [];

        foreach ($permissions as $p) {
            $selected_permissions[] = $p->id;
        }

        return View::make('user.edit_permissions', [
            'user' => $user,
            'selected_permissions' => $selected_permissions,
            'available_permissions' => $available_permissions
        ]);
    }

    public function postUserPermissions($id)
    {
        if (!$this->checkRoute()) {
            return Redirect::to('/');
        }

        $input = Input::only('selected_permissions');

        $user = User::find($id);
        $permissions = $user->permissions;

        foreach ($permissions as $p) {
            $user->permissions()->detach($p->id);
        }

        $user->permissions()->attach($input['selected_permissions']);

        //update done, refresh information
        $user = User::find($id);
        $permissions = $user->permissions;
        $available_permissions = Permission::all();

        foreach ($permissions as $p) {
            $selected_permissions[] = $p->id;
        }

        Cache::tags('user-permissions')->flush();

        return View::make('user.edit_permissions', [
            'user' => $user,
            'selected_permissions' => $selected_permissions,
            'available_permissions' => $available_permissions,
            'success' => true
        ]);
    }

    private function testRedirect()
    {
        return Redirect::intended('/');
    }

    private function isThisMyUsername($username)
    {
        if (Auth::check()) {
            if (Auth::user()->username == $username) {
                return true;
            }
        }
        return false;
    }

    private function setProfileOptions($username)
    {
        $my_profile = false;
        $show_modpacks = false;
        $show_mods = false;

        $raw_user = User::where('username', $username)->first();

        $modpacks = $raw_user->modpacks()->first();
        $mods = $raw_user->mods()->first();

        if ($modpacks && !$raw_user->hide_mods_modpacks) {
            $show_modpacks = true;
        }

        if ($mods && !$raw_user->hide_mods_modpacks) {
            $show_mods = true;
        }

        if (Auth::check()) {
            if (Auth::user()->email == $raw_user['email']) {
                $my_profile = true;

                if ($modpacks) {
                    $show_modpacks = true;
                }

                if ($mods) {
                    $show_mods = true;
                }
            }
        }


        View::share('my_profile', $my_profile);
        View::share('show_modpacks', $show_modpacks);
        View::share('show_mods', $show_mods);

        return true;
    }
}