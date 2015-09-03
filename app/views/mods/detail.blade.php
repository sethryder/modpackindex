@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{{ $mod->name }}}</u>

                </h3>

                <div style="position: relative; bottom: 15px;"><h5>by
                        <?php $i = 0 ?>
                        @foreach($authors as $index => $author)
                            {{{$author->name}}}@if ($i+1 != count($authors)), @endif
                            <?php $i++; ?>
                        @endforeach
                    </h5>

                    <p>
                        <?php $i = 0 ?>
                        @foreach ($links as $index => $link)
                            @if ($index == 'website')
                                <a href="{{{ $link }}}"><i class="fa fa-external-link"></i>Website</a>
                            @endif
                            @if ($index == 'download_link')
                                <a href="{{{ $link }}}"><i class="fa fa-download"></i>Download</a>
                            @endif
                            @if ($index == 'donate_link')
                                <a href="{{{ $link }}}"><i class="fa fa-dollar"></i>Donate</a>
                            @endif
                            @if ($index == 'wiki_link')
                                <a href="{{{ $link }}}"><i class="fa fa-book"></i>Wiki</a>
                            @endif
                            @if ($i+1 != count($links))| @endif
                            <?php $i++; ?>
                        @endforeach
                        @if (isset($user_permissions['mod_edit']) || $can_edit == true)
                            | <a href="{{ action('ModController@getEdit', [$mod->id]) }}"><i class="fa fa-edit"></i>Edit</a>
                        @endif
                    </p>

                    <div class="portlet-body"></div>
                    @if ($mod->description == '')
                        <p>{{{ $mod->deck }}}</p>
                    @else
                        <div class="mod-description">
                            {{ $mod_description }}
                        </div>
                    @endif
                    <p>&nbsp;</p>

                    <ul id="modpack_page" class="nav nav-tabs nav-tabs-sticky">
                        <li class="active">
                            <a href="#modpack_list" data-toggle="tab">Modpack List</a>
                        </li>

                        @if (isset($spotlights[0]))
                            <li class="">
                                <a href="#spotlights" data-toggle="tab">Spotlights</a>
                            </li>
                        @endif

                        @if (isset($tutorials[0]))
                            <li class="">
                                <a href="#tutorials" data-toggle="tab">Tutorials</a>
                            </li>
                        @endif
                    </ul>

                    <div id="modpack_page" class="tab-content">

                        <div class="tab-pane fade active in" id="modpack_list">

                            <div class="portlet-body">
                                <table class="table table-striped table-bordered" id="table-1">
                                    <thead>
                                    <tr>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 10%">MC Version</th>
                                        <th style="width: 36%">Description</th>
                                        <th style="width: 15%">Creators(s)</th>
                                        <th style="width: 1%"></th>
                                        <th style="width: 18%">Link(s)</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>MC Version</th>
                                        <th>Description</th>
                                        <th>Creators(s)</th>
                                        <th></th>
                                        <th>Link(s)</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.portlet-body -->

                        </div>

                        @if (isset($spotlights[0]))
                            <div class="tab-pane fade" id="spotlights">
                                <div class="row">
                                    @foreach($spotlights as $spotlight)
                                        <div class="col-md-3 col-sm-6">

                                            <div class="thumbnail">
                                                <div class="thumbnail-view">
                                                    <a href="{{ action('YoutubeController@getModVideo', [$mod->slug, $spotlight->id, Str::slug($spotlight->channel_title)]) }}">
                                                        <img src="{{{ $spotlight->thumbnail }}}" style="width: 100%"/></a>
                                                </div>
                                                <div class="thumbnail-footer">
                                                    <div class="pull-left">
                                                        <a href="http://www.youtube.com/{{{ $spotlight->channel_title }}}">{{{ $spotlight->channel_title }}}</a>
                                                    </div>

                                                    <div class="pull-right">
                                                    </div>
                                                </div>
                                            </div>

                                        </div> <!-- /.col -->
                                    @endforeach
                                </div>
                            </div> <!-- /.tab-pane -->
                        @endif

                        @if (isset($tutorials[0]))
                            <div class="tab-pane fade" id="tutorials">
                                <div class="row">
                                    @foreach($tutorials as $tutorial)
                                        <div class="col-md-3 col-sm-6">

                                            <div class="thumbnail">
                                                <div class="thumbnail-view">
                                                    <a href="{{ action('YoutubeController@getModVideo', [$tutorial->slug, $tutorial->id, Str::slug($tutorial->channel_title)]) }}">
                                                        <img src="{{{ $tutorial->thumbnail }}}" style="width: 100%"/></a>
                                                </div>
                                                <div class="thumbnail-footer">
                                                    <div class="pull-left">
                                                        <a href="http://www.youtube.com/{{{ $tutorial->channel_title }}}">{{{ $tutorial->channel_title }}}</a>
                                                    </div>

                                                    <div class="pull-right">
                                                    </div>
                                                </div>
                                            </div>

                                        </div> <!-- /.col -->
                                    @endforeach
                                </div>
                            </div> <!-- /.tab-pane -->
                        @endif
                    </div>

                    <div class="pull-right"><p><a href="{{ action('StaticPagesController@getContact') }}?mod={{{ $mod->id }}}">Something incorrect?</a></p></div>

                </div>
                <!-- /.portlet -->

            </div>
            <!-- /.container -->

        </div>
        <!-- .content -->
    </div>
@stop