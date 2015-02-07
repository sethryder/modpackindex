<?php

class SitemapController extends BaseController
{
    private $sitemap;
    private $minecraft_version_array;

    public function __construct()
    {
        $this->sitemap = App::make("sitemap");
        $this->minecraft_version_array = [];

        $minecraft_versions = MinecraftVersion::all();

        foreach ($minecraft_versions as $version)
        {
            $version_id = $version->id;
            $this->minecraft_version_array[$version_id] = preg_replace('/\./', '-', $version->name);
        }
    }

    public function getSitemapIndex()
    {
        $this->sitemap->addSitemap(URL::to('/sitemap/main.xml'));
        $this->sitemap->addSitemap(URL::to('/sitemap/launchers.xml'));
        $this->sitemap->addSitemap(URL::to('/sitemap/modpacks.xml'));
        $this->sitemap->addSitemap(URL::to('/sitemap/mods.xml'));

        return $this->sitemap->render('sitemapindex');
    }

    public function getSitemapMain()
    {
        //manual pages
        $this->sitemap->add(URL::to('/'));
        $this->sitemap->add(URL::to('about'));
        $this->sitemap->add(URL::to('contact'));
        $this->sitemap->add(URL::to('submit-modpack'));
        $this->sitemap->add(URL::to('about/modpack-codes'));
        $this->sitemap->add(URL::to('streams'));

        //pack finder
        $this->sitemap->add(URL::to('modpack/finder'));

        return $this->sitemap->render('xml');
    }

    public function getSitemapLaunchers()
    {
        $launchers = Launcher::orderBy('created_at', 'desc')->get();

        foreach ($launchers as $launcher)
        {
            $this->sitemap->add(URL::to('launcher/' . $launcher->slug));

            foreach ($this->minecraft_version_array as $version)
            {
                $this->sitemap->add(URL::to('launcher/' . $launcher->slug . '/' . $version));
            }
        }

        return $this->sitemap->render('xml');
    }

    public function getSitemapModpacks()
    {
        $modpacks = Modpack::orderBy('created_at', 'desc')->get();

        //index
        $this->sitemap->add(URL::to('modpacks'));

        foreach ($this->minecraft_version_array as $version)
        {
            $this->sitemap->add(URL::to('modpacks/' . $version));
        }

        foreach ($modpacks as $modpack)
        {
            $version_id = $modpack->minecraft_version_id;
            $this->sitemap->add(URL::to('modpack/' . $this->minecraft_version_array[$version_id] . '/' . $modpack->slug), $modpack->updated_at);
        }

        return $this->sitemap->render('xml');
    }

    public function getSitemapMods()
    {
        $mods = Mod::orderBy('created_at', 'desc')->get();

        //index
        $this->sitemap->add(URL::to('mods'));

        foreach ($this->minecraft_version_array as $version)
        {
            $this->sitemap->add(URL::to('mods/' . $version));
        }

        foreach ($mods as $mod)
        {
            $this->sitemap->add(URL::to('mod/' . $mod->slug), $mod->updated_at);
        }

        return $this->sitemap->render('xml');
    }
}