<?php

class AdminController extends BaseController
{
    public function getClearCache($tag = 'all')
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        if ($tag == 'all')
        {
            Cache::flush();
            echo "Cleared all cache.";
        }
        else
        {
            Cache::tags($tag)->flush();
            echo "Cleared cache with tag: $tag.";
        }
    }
}