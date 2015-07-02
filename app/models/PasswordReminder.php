<?php

class PasswordReminder extends Eloquent
{
    protected $table = 'password_reminders';

    public function user()
    {
        return $this->belongsTo('User');
    }
}