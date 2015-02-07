<?php

class TwitchController extends BaseController
{
    public function getChannel($channel)
    {
        $channel = TwitchStream::where('display_name', $channel)->first();
        $modpack = $channel->modpack;

        if (!$modpack)
        {
            $modpack_name = 'Unknown';
        }
        else
        {
            $modpack_name = $modpack->name;
        }

        $broadcaster_language = strtoupper($channel->broadcaster_language);

        $title = $channel->display_name . ' Streaming ' . $modpack_name . ' - ' . $this->site_name;

        return View::make('twitch.channel', ['title' => $title, 'channel' => $channel, 'modpack_name' => $modpack_name,
            'broadcaster_language' => $broadcaster_language]);
    }

    public function getStreams()
    {
        $channels = TwitchStream::where('online', 1)->get();
        $modpack = $channel->modpack;

        foreach ($channels as $chan)

        $title = $channel->display_name . ' Streaming ' . $modpack_name . ' - ' . $this->site_name;

        return View::make('twitch.channel', ['title' => $title, 'channel' => $channel, 'modpack_name' => $modpack_name]);
    }
}