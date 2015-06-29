<?php

class UserInfo extends Eloquent
{
    protected $table = 'users_info';

    public function user()
    {
        return $this->belongsTo('User');
    }
}