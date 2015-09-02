<?php

class LauncherController extends BaseController
{
    use \App\TraitCommon;

    public function getLauncherVersion($name, $version = 'all')
    {
        $table_javascript = route('tdf_name', ['launchers', $version, $name]);
        $version = $this->getVersion($version);

        if ($version == 'all') {
            $version = 'All';
        }

        $launcher = Launcher::where('slug', '=', $name)->first();

        if (!$launcher) {
            App::abort(404);
        }

        $raw_links = [
            'website' => $launcher->website,
            'download_link' => $launcher->download_link,
            'donate_link' => $launcher->donate_link,
            'wiki_link' => $launcher->wiki_link,
        ];

        $links = [];

        foreach ($raw_links as $index => $link) {
            if ($link != '') {
                $links["$index"] = $link;
            }
        }

        $title = $version . ' ' . $launcher->name . ' Modpacks - ' . $this->site_name;
        $meta_description = $version . ' Modpacks for ' . $launcher->name;

        return View::make('launchers.list', [
            'table_javascript' => $table_javascript,
            'version' => $version,
            'launcher' => $launcher,
            'links' => $links,
            'title' => $title,
            'meta_description' => $meta_description
        ]);
    }
}