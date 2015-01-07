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

    public function getRoute()
    {
        $route = Route::currentRouteName();

        print_r($route);
    }

    public function getEnv()
    {
        print_r(App::environment());
    }

    public function getCache()
    {
        print_r(Cache::getMemory());
    }

    public function getClear()
    {
        Cache::tags('launchers')->flush();
        Cache::tags('modpacks')->flush();
        Cache::tags('modpackmods')->flush();
        Cache::tags('modmodpacks')->flush();
        Cache::tags('mods')->flush();

        echo 'Cache cleared.';
    }
}