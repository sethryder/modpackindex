<?php

class ServerTag extends Eloquent
{
    protected $table = 'server_tags';

    public function servers()
    {
        return $this->belongsToMany('Server');
    }
}