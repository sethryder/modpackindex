<?php

class TestController extends BaseController
{
    public function getLayout()
    {
        return View::Make('test');
    }

    public function getList()
    {
        return View::Make('mods.list');
    }

    public function mods()
    {
        return Mod::all();
    }

    public function getCheckAuth()
    {
        if (Auth::check())
        {
            echo 'You are login!';
        }
        else
        {
            echo 'You are not login!';
        }
    }
}