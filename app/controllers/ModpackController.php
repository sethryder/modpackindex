<?php

class ModpackController extends BaseController
{
    public function getModpackVersion($version='all')
    {
        $table_javascript = '/api/table/modpacks_'. $version .'.json';
        $version = preg_replace('/-/', '.', $version);

        if ($version == 'all') $version = 'All';

        $title = $version . ' Modpacks - '. $this->site_name;

        return View::make('modpacks.list', array('table_javascript' => $table_javascript, 'version' => $version,
            'title'=> $title));
    }

    public function getModpack($version, $slug)
    {
        $table_javascript = '/api/table/modpackmods_'.$version.'/'. $slug .'.json';

        $modpack = Modpack::where('slug', '=', $slug)->first();
        $launcher = $modpack->launcher;

        $raw_links = [
            'website'       => $modpack->website,
            'download_link' => $modpack->download_link,
            'donate_link'  => $modpack->donate_link,
            'wiki_link'     => $modpack->wiki_link,
        ];

        $links = [];

        $title = $modpack->name . ' - Modpack - '. $this->site_name;

        foreach ($raw_links as $index => $link)
        {
            if ($link != '')
            {
                $links["$index"] = $link;
            }
        }

        return View::make('modpacks.detail', array('table_javascript' => $table_javascript, 'modpack' => $modpack,
            'links' => $links, 'launcher' => $launcher, 'title' => $title));
    }
}