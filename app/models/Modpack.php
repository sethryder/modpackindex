<?php

class Modpack extends Eloquent
{
    public function mods()
    {
        return $this->belongsToMany('Mod');
    }

    public function launcher()
    {
        return $this->belongsTo('Launcher');
    }
}