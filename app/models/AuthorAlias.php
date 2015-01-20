<?php

class AuthorAlias extends Eloquent
{
    protected $table = 'author_aliases';

    public function author()
    {
        return $this->belongsTo('Author');
    }
}