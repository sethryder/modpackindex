<?php

class Creator extends Eloquent
{
    public function mods()
    {
        $this->belongsToMany('Modpack');
    }
}