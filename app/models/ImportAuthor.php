<?php

class ImportAuthor extends Eloquent
{
    public function import()
    {
        return $this->belongsTo('Import');
    }
}