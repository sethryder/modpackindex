<?php

class Mod extends Eloquent
{
    public function versions()
    {
        return $this->belongsToMany('MinecraftVersion');
    }

    public function authors()
    {
        return $this->belongsToMany('Author');
    }

    public function modpacks()
    {
        return $this->belongsToMany('Modpack');
    }
}