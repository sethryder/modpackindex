<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    public function permissions()
    {
        return $this->belongsToMany('Permission');
    }

    public function info()
    {
        return $this->hasOne('UserInfo');
    }

    public function password_reminder()
    {
        return $this->hasOne('PasswordReminder');
    }

    public function mods()
    {
        return $this->belongsToMany('Mod');
    }

    public function modpacks()
    {
        return $this->belongsToMany('Modpack');
    }

    public function servers()
    {
        return $this->hasMany('Server');
    }
}
