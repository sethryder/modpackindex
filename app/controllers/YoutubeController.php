<?php

class YoutubeController extends BaseController
{
    public $categories = [
        '1' => 'Let\'s Play',
        '2' => 'Spotlight',
        '3' => 'Tutorial',
    ];

    public function getModpackVideo($version=null, $slug=null, $id)
    {
        $video = Youtube::find($id);

        if (!$video)
        {
            App::abort(404);
        }

        if ($video->category_id == 1)
        {
            $title_subject = 'Let\'s Play';
        }
        else
        {
            $title_subject = 'Video';
        }

        $modpack = $video->modpack;

        $title = $modpack->name . ' - ' . $title_subject . ' by ' . $video->channel_title . ' - ' . $this->site_name;

        return View::make('youtube.detail', ['title' => $title, 'video' => $video, 'parent_item' => $modpack]);

    }

    public function getModVideo($slug=null, $id)
    {
        $video = Youtube::find($id);

        if (!$video)
        {
            App::abort(404);
        }

        if ($video->category_id == 2)
        {
            $title_subject = 'Spotlight';
        }
        elseif ($video->category_id == 3)
        {
            $title_subject = 'Tutorial';
        }
        else
        {
            $title_subject = 'Video';
        }

        $mod = $video->mod;

        $title = $mod->name . ' - ' . $title_subject . ' by ' . $video->channel_title . ' - ' . $this->site_name;
        //$title = $video->channel_title . ' - ' . $mod->name . ' ' .$title_subject . ' - ' . $this->site_name;

        return View::make('youtube.detail', ['title' => $title, 'video' => $video, 'parent_item' => $mod]);
    }

    public function getAdd()
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Youtube Video / Playlist - ' . $this->site_name;

        return View::make('youtube.add', ['chosen' => true, 'title' => $title, 'categories' => $this->categories]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Youtube Video / Playlist - ' . $this->site_name;

        $input = Input::only('url', 'category', 'modpack', 'mod');

        $validator = Validator::make($input,
            [
                'url' => 'required',
            ]);

        if ($validator->fails())
        {
            return Redirect::to('/youtube/add')->withErrors($validator)->withInput();
        }
        else
        {
            $youtube = New Youtube;

            $processed_url = $this->processURL($input['url']);

            if (!$processed_url['type'])
            {
                return Redirect::to('/youtube/add')->withErrors(['message' => 'Unable to process URL.'])->withInput();
            }

            $youtube_information = $youtube->getVideoInfo($processed_url['id'], $processed_url['type']);

            if (!$youtube_information)
            {
                return Redirect::to('/youtube/add')->withErrors(['message' => 'Unable to process Youtube API.'])->withInput();
            }

            $youtube_information = $youtube_information->items[0];

            $youtube->type = $processed_url['type'];
            $youtube->title = $youtube_information->snippet->title;
            $youtube->youtube_id = $youtube_information->id;
            $youtube->channel_title = $youtube_information->snippet->channelTitle;
            $youtube->channel_id = $youtube_information->snippet->channelId;
            $youtube->thumbnail = $youtube_information->snippet->thumbnails->medium->url;
            $youtube->category_id = $input['category'];

            if ($input['modpack'])
            {
                $youtube->modpack_id = $input['modpack'];
            }
            elseif ($input['mod'])
            {
                $youtube->mod_id = $input['mod'];
            }

            $youtube->last_ip = Request::getClientIp();
            $success = $youtube->save();

            if ($success)
            {
                Cache::tags('modpacks')->flush();
                Cache::tags('mods')->flush();
                Queue::push('BuildCache');
                return View::make('youtube.add', ['title' => $title, 'success' => true, 'categories' => $this->categories]);
            }
            else
            {
                return Redirect::to('/youtube/add')->withErrors(['message' => 'Unable to add modpack code.'])->withInput();
            }

        }
    }

    public function getEdit()
    {

    }

    public function postEdit()
    {

    }

    private function processURL($url)
    {
        $playlist_regex = '/(?<=list=)(.*?)(?=$|&)/i';
        $video_regex = '/(?<=v=)(.*?)(?=$|&)/i';
        $url_array = [];

        preg_match($playlist_regex, $url, $playlist_match);
        preg_match($video_regex, $url, $video_match);

        if (isset($playlist_match[0]))
        {
            $url_array['type'] = 2;
            $url_array['id'] = $playlist_match[0];
        }
        elseif (isset$video_match[0]))
        {
            $url_array['type'] = 1;
            $url_array['id'] = $video_match[0];
        }

        return $url_array;
    }
}