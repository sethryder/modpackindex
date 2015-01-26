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

    public function version()
    {
        return $this->belongsTo('MinecraftVersion', 'minecraft_version_id');
    }

    public function creators()
    {
        return $this->belongsToMany('Creator');
    }

    public function code()
    {
        return $this->hasOne('ModpackCode');
    }

    public function tags()
    {
        return $this->belongsToMany('ModpackTag');
    }
}