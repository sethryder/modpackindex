<?php

class Creator extends Eloquent
{
    public function modpacks()
    {
        $this->belongsToMany('Modpack');
    }
}