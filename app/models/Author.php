<?php

class Author extends Eloquent
{
    public function mods()
    {
        $this->belongsToMany('Mod');
    }

    public function aliases()
    {
        $this->hasMany('AuthorAlias');
    }
}