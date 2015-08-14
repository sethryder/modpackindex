<?php

class ServerUser extends Eloquent
{
    protected $table = 'server_users';

    public function server()
    {
        return $this->belongsTo('Server');
    }
}