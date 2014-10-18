<?php

class Author extends Eloquent
{
    public function mods()
    {
        $this->belongsToMany('Mod');
    }
}