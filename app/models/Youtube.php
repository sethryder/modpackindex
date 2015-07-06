<?php

class Youtube extends Eloquent
{
    protected $table = 'youtube';

    public function mod()
    {
        return $this->belongsTo('Mod');
    }

    public function modpack()
    {
        return $this->belongsTo('Modpack');
    }

    public static function getVideoInfo($id, $type)
    {
        $youtube_url = Config::get('services.youtube.url');
        $youtube_api_key = Config::get('services.youtube.key');

        if ($type == 1) {
            $url = $youtube_url . 'videos?id=' . $id . '&key=' . $youtube_api_key . '&part=snippet';
        } elseif ($type == 2) {
            $url = $youtube_url . 'playlists?id=' . $id . '&key=' . $youtube_api_key . '&part=snippet';
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $raw_body = $response->getBody();
        $decoded_body = json_decode($raw_body);

        if (!$decoded_body) {
            return false;
        } else {
            return $decoded_body;
        }
    }
}