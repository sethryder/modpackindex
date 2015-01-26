<?php

class ModpackTag extends Eloquent
{
    protected $table = 'modpack_tags';

    public function modpacks()
    {
        return $this->belongsToMany('Modpack');
    }
}