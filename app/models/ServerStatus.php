<?php

class ServerStatus extends Eloquent
{
    protected $table = 'server_status';

    public function server()
    {
        return $this->belongsTo('Server');
    }
}