<?php

class ModpackCode extends Eloquent
{
    protected $table = 'modpack_codes';

    public function modpack()
    {
        return $this->belongsTo('Modpack');
    }
}