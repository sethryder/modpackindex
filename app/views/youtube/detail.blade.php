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
                            <iframe width="1920" height="1080" src="https://www.youtube.com/embed/DFBkUc6tAPk?list=PLaiPn4ewcbkEC_hsSjPSqN8Mz3qWVC_MA" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <br />
                    <h3><a href="{{ URL::previous() }}#tab_youtube">Back</a></h3>

                </div> <!-- /.portlet -->

            </div> <!-- /.container -->

        </div> <!-- .content -->
    </div>
@stop