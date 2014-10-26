<?php

class StaticPagesController extends BaseController
{
    public function getAbout()
    {
        return View::Make('about');
    }
}