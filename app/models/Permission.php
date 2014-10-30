<?php

class Permission extends Eloquent
{
    public function Users()
    {
        $this->belongsToMany('User');
    }
}