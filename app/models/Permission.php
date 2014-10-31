<?php

class Permission extends Eloquent
{
    public function Users()
    {
        return $this->belongsToMany('User');
    }
}