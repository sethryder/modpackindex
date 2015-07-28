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

    public function aliases()
    {
        return $this->hasMany('ModpackAlias');
    }

    public function servers()
    {
        return $this->hasMany('Server');
    }

    public function twitchStreams()
    {
        return $this->hasMany('TwitchStream');
    }

    public function youtubeVideos()
    {
        return $this->hasMany('Youtube');
    }

    public function maintainers()
    {
        return $this->belongsToMany('User');
    }
}