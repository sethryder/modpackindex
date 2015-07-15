<?php

class TwitchController extends BaseController
{
    public function getChannel($channel)
    {
        $channel = TwitchStream::where('display_name', $channel)->first();

        if (!$channel) {
            return Redirect::to('/streams');
        }

        $modpack = $channel->modpack;

        if (!$modpack) {
            $modpack_name = 'Unknown';
        } else {
            $modpack_name = $modpack->name;
        }

        $broadcaster_language = strtoupper($channel->broadcaster_language);

        $title = $channel->display_name . ' Streaming ' . $modpack_name . ' - ' . $this->site_name;
        $meta_description = 'Watch ' . $channel->display_name . ' play your favorite Modpack.';

        return View::make('twitch.channel', [
            'title' => $title,
            'channel' => $channel,
            'modpack_name' => $modpack_name,
            'broadcaster_language' => $broadcaster_language,
            'meta_description' => $meta_description
        ]);
    }

    public function getStreams()
    {
        $streams_array = [];

        $modpacks = Modpack::orderBy('name')->with(array(
            'twitchStreams' => function ($query) {
                $query->orderBy('viewers', 'desc');
            }
        ))->get();

        foreach ($modpacks as $modpack) {
            if ($modpack->twitchStreams) {
                $streams_array[$modpack->name] = $modpack->twitchStreams;
            }
        }

        $title = 'Modpack Streams - ' . $this->site_name;
        $meta_description = 'Modpack Stream List. Find and watch a Twitch Stream for your favorite modpack.';

        return View::make('twitch.streams', [
            'title' => $title,
            'streams' => $streams_array,
            'meta_description' => $meta_description
        ]);
    }
}