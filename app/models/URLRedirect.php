<?php

class URLRedirect extends Eloquent
{
    protected $table = 'url_redirects';

    public function getRedirect($source)
    {
        $target = $this->where('source', '=', $source)->first();

        if ($target)
        {
            return $target;
        }
        else
        {
            return false;
        }
    }
}