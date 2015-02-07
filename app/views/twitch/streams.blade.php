@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            @foreach($streams as $modpack_name => $modpack_streams)
                @if(count($modpack_streams) > 0)
                    <h3 class="portlet-title">
                        <u>{{ $modpack_name }}</u>
                    </h3>

                    @foreach($modpack_streams as $stream)
                        <div class="row">
                                <div class="col-md-3 col-sm-6">

                                    <div class="thumbnail">
                                        <div class="thumbnail-view">
                                            <a href="/stream/{{ $stream->display_name }}">
                                                <img src="{{ $stream->preview }}" style="width: 100%" /></a>
                                        </div>
                                        <div class="thumbnail-footer">
                                            <div class="pull-left">
                                                <a href="http://twitch.tv/{{ $stream->display_name }}">{{ $stream->display_name }}</a>
                                            </div>

                                            <div class="pull-right">
                                                <a href="/stream/{{ $stream->display_name }}"><i class="fa fa-bullhorn"></i> {{ $stream->language }}</a>
                                                <a href="/stream/{{ $stream->display_name }}"><i class="fa fa-eye"></i> {{ $stream->viewers }}</a>
                                                <!-- <a href="javascript:;"><i class="fa fa-heart"></i> {{ $stream->followers }}</a> -->
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- /.col -->
                            </div>
                    @endforeach
                @endif
            @endforeach

            </div> <!-- /.container -->

        </div> <!-- .content -->
    </div>
@stop