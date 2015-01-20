<?php

class Import extends Eloquent
{
    public function authors()
    {
        return $this->hasMany('ImportAuthor', 'import_id');
    }

    public function downloadModInfo($url)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        if ($response->getStatusCode() != 200)
        {
            return false;
        }

        $raw_body = $response->getBody();
        $decoded_body = json_decode($raw_body);

        if (!$decoded_body)
        {
            return false;
        }
        else
        {
            return $decoded_body;
        }
    }
}