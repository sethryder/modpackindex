<?php

class ModpackAlias extends Eloquent
{
    protected $table = 'modpack_aliases';

    public function modpack()
    {
        return $this->belongsTo('Modpack');
    }
}