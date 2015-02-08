@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{ $video->title }} (
                    @if ($video->type == 1)
                        Video
                    @elseif ($video->type == 2)
                        Playlist
                    @endif
                    )</u>
                </h3>

                <div style="position: relative; bottom: 15px;"><h5>by {{ $video->channel_title }}
                </h5>

                    <div class="portlet-body" style="text-align:center;">
                        <div class="video-container">
                            @if ($video->type == 1)
                                <iframe width="1920" height="1080" src="https://www.youtube.com/embed/{{ $video->youtube_id }}" frameborder="0" allowfullscreen></iframe>
                            @elseif ($video->type == 2)
                                <iframe width="1920" height="1080" src="https://www.youtube.com/embed/?list={{ $video->youtube_id }}" frameborder="0" allowfullscreen></iframe>
                            @endif
                        </div>
                    </div>
                    <br />
                    <h3><a href="{{ URL::previous() }}#tab_youtube">Back</a></h3>

                </div> <!-- /.portlet -->

            </div> <!-- /.container -->

        </div> <!-- .content -->
    </div>
@stop