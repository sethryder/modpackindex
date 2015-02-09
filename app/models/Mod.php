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

    public function youtubeVideos()
    {
        return $this->hasMany('Youtube');
    }

    public function requiredMods()
    {
        return $this->belongsToMany('Mod', 'mod_requirements', 'mod_id', 'required_mod_id');
    }

    public function requiredFor()
    {
        return $this->belongsToMany('Mod', 'mod_requirements', 'required_mod_id', 'mod_id');
    }
}