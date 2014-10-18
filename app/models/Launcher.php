<?php

class Launcher extends Eloquent
{
    public function modpacks()
    {
        return $this->hasMany('Modpack');
    }
}