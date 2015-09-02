<?php

namespace App;

use Illuminate\Support\Facades\Response;

trait TraitCommon
{
    public function getVersion($version)
    {
        return preg_replace('/-/', '.', $version);
    }

    public function buildModpackArray(\Modpack $modpack)
    {
        $creators = '';
        $links = '';

        $name = link_to_action('ModpackController@getModpack', $modpack->name, [
            $this->getVersion($modpack->version->name),
            $modpack->slug,
        ]);

        switch ($modpack->launcher->short_name) {
            case 'ftb':
                $icon = asset('/static/img/icons/ftb.png');
                break;
            case 'atlauncher':
                $icon = asset('/static/img/icons/atlauncher.png');
                break;
            case 'technic':
                $icon = asset('/static/img/icons/technic.png');
                break;
            case 'curse':
                $icon = asset('/static/img/icons/curse.png');
                break;
            default:
                $icon = asset('/static/img/icons/custom.png');
        }

        $icon_html = [
            'icon' => $icon,
            'link' => action('LauncherController@getLauncherVersion', [$modpack->launcher->slug]),
            'title' => $modpack->launcher->short_name ? $modpack->launcher->short_name : 'custom',
            'launcher_id' => $modpack->launcher->id,
        ];

        foreach ($modpack->creators as $v) {
            $creators .= $v->name;
            $creators .= ', ';
        }

        if (!$creators) {
            $creators = 'N/A';
        }

        $version = $modpack->version->name;

        if ($modpack->website) {
            $links .= link_to($modpack->website, 'Website');
            $links .= ' / ';
        }

        if ($modpack->donate_link) {
            $links .= link_to($modpack->donate_link, 'Donate');
            $links .= ' / ';
        }

        if ($modpack->wiki_link) {
            $links .= link_to($modpack->wiki_link, 'Wiki');
            $links .= ' / ';
        }

        return [
            'icon_html' => $icon_html,
            'name' => $name,
            'icon' => $icon,
            'deck' => $modpack->deck,
            'links' => rtrim($links, ' / '),
            'version' => $version,
            'creators' => rtrim($creators, ', '),
        ];
    }

    private function buildModArray($mod)
    {
        $supported_versions = '';
        $authors = '';
        $links = '';
        $version_array = [];
        $i = 0;

        $name = link_to_action('ModController@getMod', $mod->name, [$mod->slug]);

        foreach ($mod->versions as $v) {
            if (!in_array($v->name, $version_array)) {
                $version_array[] = $v->name;
                $supported_versions .= $v->name;
                $supported_versions .= ', ';
            }
            $i++;
        }

        if (!$supported_versions) {
            $supported_versions = 'Unknown';
        }

        foreach ($mod->authors as $v) {
            $authors .= $v->name;
            $authors .= ', ';
        }

        if (!$authors) {
            $authors = 'N/A';
        }

        if ($mod->website) {
            $links .= link_to($mod->website, 'Website');
            $links .= ' / ';
        }

        if ($mod->donate_link) {
            $links .= link_to($mod->donate_link, 'Donate');
            $links .= ' / ';
        }

        if ($mod->wiki_link) {
            $links .= link_to($mod->wiki_link, 'Wiki');
            $links .= ' / ';
        }

        return [
            'name' => $name,
            'deck' => $mod->deck,
            'links' => rtrim($links, ' / '),
            'versions' => rtrim($supported_versions, ', '),
            'authors' => rtrim($authors, ', '),
        ];
    }

    public function buildDTModOutput($mod_data)
    {
        // rebuild the data table array to add the dt row id field
        $dtArray = [];
        $i = 0;
        foreach ($mod_data as $row) {
            $row['DT_RowId'] = $i;
            $dtArray[] = $row;
            $i++;
        }

        return Response::json(['id' => -1, 'fieldErrors' => '', 'sError' => '', 'aaData' => $dtArray]);
    }

    public function buildDTModpackOutput($modpack_data)
    {
        // rebuild the data table array to add the dt row id field
        $dtArray = [];
        $i = 0;
        foreach ($modpack_data as $row) {
            $row['DT_RowId'] = $i;
            $dtArray[] = $row;
            $i++;
        }

        return Response::json(['id' => -1, 'fieldErrors' => '', 'sError' => '', 'aaData' => $dtArray]);
    }

    public function buildDTLauncherOutput($modpack_data)
    {
        // rebuild the data table array to add the dt row id field
        $dtArray = [];
        $i = 0;
        foreach ($modpack_data as $row) {
            $row['DT_RowId'] = $i;
            $dtArray[] = $row;
            $i++;
        }

        return Response::json(['id' => -1, 'fieldErrors' => '', 'sError' => '', 'aaData' => $dtArray]);
    }

    public function buildDTServerOutput($server_data)
    {
        // rebuild the data table array to add the dt row id field
        $dtArray = [];
        $i = 0;
        foreach ($server_data as $row) {
            $row['DT_RowId'] = $i;
            $dtArray[] = $row;
            $i++;
        }

        return Response::json(['id' => -1, 'fieldErrors' => '', 'sError' => '', 'aaData' => $dtArray]);
    }

    public function buildDTServerPlayerOutput($server_data)
    {
        // rebuild the data table array to add the dt row id field
        $dtArray = [];
        $i = 0;
        foreach ($server_data as $row) {
            $row['DT_RowId'] = $i;
            $dtArray[] = $row;
            $i++;
        }

        return Response::json(['id' => -1, 'fieldErrors' => '', 'sError' => '', 'aaData' => $dtArray]);
    }

    public function buildDTServerModOutput($mod_data)
    {
        // rebuild the data table array to add the dt row id field
        $dtArray = [];
        $i = 0;
        foreach ($mod_data as $row) {
            $row['DT_RowId'] = $i;
            $dtArray[] = $row;
            $i++;
        }

        return Response::json(['id' => -1, 'fieldErrors' => '', 'sError' => '', 'aaData' => $dtArray]);
    }
}