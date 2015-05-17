<?php

class AdminController extends BaseController
{
    public function getClearCache($tag = 'all')
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        if ($tag == 'all')
        {
            Cache::flush();
            echo "<p>Cleared all cache.</p>";
            echo "<p><a href=\"/\">Go Home</a></p>";
        }
        else
        {
            Cache::tags($tag)->flush();
            echo "<p>Cleared cache with tag: $tag.</p>";
            echo "<p><a href=\"/\">Go Home</a></p>";
        }
    }

    public function getMemcacheStats()
    {
        $m = new Memcached();
        $m->addServer('localhost', 11211);

        print_r($m->getStats());
    }
}