<?php

class MinecraftVersion extends Eloquent
{
    protected $table = 'minecraft_versions';

    public function mods()
    {
        return $this->belongsToMany('Mod');
    }

    public function modpacks()
    {
        return $this->hasMany('Modpack');
    }
}