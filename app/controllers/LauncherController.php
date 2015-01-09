<?php

class LauncherController extends BaseController
{
    public function getLauncherVersion($name, $version='all')
    {
        $table_javascript = '/api/table/launchers_'. $version .'/'. $name .'.json';
        $version = preg_replace('/-/', '.', $version);

        if ($version == 'all') $version = 'All';

        $launcher = Launcher::where('slug', '=', $name)->first();

        if (!$launcher)
        {
            App::abort(404);
        }

        $raw_links = [
            'website'       => $launcher->website,
            'download_link' => $launcher->download_link,
            'donate_link'  => $launcher->donate_link,
            'wiki_link'     => $launcher->wiki_link,
        ];

        $links = [];

        foreach ($raw_links as $index => $link)
        {
            if ($link != '')
            {
                $links["$index"] = $link;
            }
        }

        $title = $version . ' ' . $launcher->name . ' Modpacks - '. $this->site_name;

        return View::make('launchers.list', array('table_javascript' => $table_javascript, 'version' => $version,
            'launcher' => $launcher, 'links' => $links, 'title' => $title));
    }
}