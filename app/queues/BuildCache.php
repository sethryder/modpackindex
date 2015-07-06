<?php

class BuildCache
{
    public function fire($job)
    {
        $versions = MinecraftVersion::all();

        foreach ($versions as $version) {
            $url_version = preg_replace('/\./', '-', $version->name);

            $this->getPage('api/table/mods/' . $url_version . '.json');
            $this->getPage('api/table/modpacks/' . $url_version . '.json');
        }

        $this->getPage('api/table/mods/all.json');
        $this->getPage('api/table/modpacks/all.json');

        $job->delete();
    }

    private function getPage($target)
    {
        $url_base = Config::get('app.url');
        $url = $url_base . $target;

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        return $response->getStatusCode();
    }
}