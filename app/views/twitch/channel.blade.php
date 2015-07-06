@extends('layouts.base')

@section('content')
    <script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>
        $(function () {
            window.onPlayerEvent = function (data) {
                data.forEach(function (event) {
                    if (event.event == "playerInit") {
                        var player = $("#twitch_embed_player")[0];
                        player.playVideo();
                        player.mute();
                    }
                });
            }

            swfobject.embedSWF("//www-cdn.jtvnw.net/swflibs/TwitchPlayer.swf", "twitch_embed_player", "1920", "1080", "11", null,
                    {
                        "eventsCallback": "onPlayerEvent",
                        "embed": 1,
                        "channel": "{{ $channel->display_name }}",
                        "auto_play": "true"
                    },
                    {
                        "allowScriptAccess": "always",
                        "allowFullScreen": "true"
                    });
        });
    </script>

    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{ $channel->display_name }}</u>
                </h3>

                <div style="position: relative; bottom: 15px;"><h5>
                        Modpack: {{ $modpack_name }}
                    </h5>

                    <div class="portlet-body" style="text-align:center;">
                        <h3>{{ $channel->status }}</h3>

                        <div class="video-container">
                            <div id="twitch_embed_player"></div>
                        </div>
                    </div>
                    <br/>

                    <h3><a href="{{ URL::previous() }}#twitch_streams">Back</a></h3>

                </div>
                <!-- /.portlet -->

            </div>
            <!-- /.container -->

        </div>
        <!-- .content -->
    </div>
@stop