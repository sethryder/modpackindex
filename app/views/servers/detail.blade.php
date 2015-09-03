@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{{ $server->name }}}</u>

                </h3>

                <div style="position: relative; bottom: 15px;"><h5>
                        Modpack: <a href="{{ action('ModpackController@getModpack', [$version_slug, $modpack->slug]) }}">{{{ $modpack->name }}}</a>
                    </h5>
                    <p>
                        @if ($server->server_address_hide)
                            <i class="fa fa-play-circle" title="Host"></i>Private
                        @else
                            <i class="fa fa-play-circle" title="Host"></i>{{{ $server->ip_host }}}:{{{ $server->port }}}
                        @endif
                        |
                        <?php $i = 0 ?>
                        @foreach ($links as $index => $link)
                            @if ($index == 'website')
                                <a href="{{{ $link }}}"><i class="fa fa-external-link"></i>Website</a>
                            @endif
                            @if ($index == 'application_url')
                                <a href="{{{ $link }}}"><i class="fa fa-pencil"></i>Application</a>
                            @endif
                            @if ($i+1 != count($links))| @endif
                            <?php $i++; ?>
                        @endforeach
                        @if (isset($user_permissions['server_edit']) || $can_edit == true)
                            | <a href="{{ action('ServerController@getEdit', [$server->id]) }}"><i class="fa fa-edit"></i>Edit</a>
                        @endif
                    </p>
                    <p>
                        <i class="fa fa-users" title="Players"></i>{{{ $status->current_players }}} / {{{ $status->max_players }}}
                    |
                        @if ($server->permissions == 1)
                            <i class="fa fa-lock" title="Whitelist"></i>Whitelist
                        @elseif ($server->permissions == 2)
                            <i class="fa fa-lock" title="Greylist"></i>Greylist
                        @elseif ($server->permissions == 3)
                            <i class="fa fa-unlock-alt" title="Open"></i>Open
                        @endif
                    |
                        <span class="flag-icon flag-icon-{{{ $country_code }}}" title="{{{ $country_name }}}"></span>{{{ $country_name }}}


                    @if (count($tags) > 0)
                        | <?php $i = 0 ?>
                            <i class="fa fa-tags"></i>Tags:
                            @foreach ($tags as $tag)
                                <a href="{{ action('ServerController@getServers') }}?tags={{{ $tag->slug }}}"
                                   title="{{{ $tag->deck }}}">{{{ $tag->name }}}</a>@if ($i+1 != count($tags)),@endif
                                <?php $i++; ?>
                            @endforeach</p>
                    @endif

                    <p>
                        <i class="fa fa-globe"></i>Last World Reset:
                        @if ($server->last_world_reset)
                            {{{ $server->last_world_reset }}}
                        @else
                            Unknown
                        @endif
                        | <i class="fa fa-globe"></i>Next World Reset:
                    @if ($server->next_world_reset)
                            {{{ $server->next_world_reset }}}
                        @else
                            Unknown
                        @endif

                    </p>

                    <hr>

                    <div class="portlet-body"></div>
                    @if ($server->description == '')
                        <p>{{{ $server->deck }}}</p>
                    @else
                        <div class="mod-description">
                            {{ $description }}
                        </div>
                    @endif
                    <p>&nbsp;</p>

                    <ul id="server-details" class="nav nav-tabs nav-tabs-sticky">
                        <li class="active">
                            <a href="#server-details" data-toggle="tab">Details</a>
                        </li>
                    </ul>

                    <div id="server-details" class="tab-content">

                        <div class="tab-pane fade active in" id="server-details">

                            <div class="row">

                                <div class="col-sm-6">

                                    <h4 class="content-title"><u>Players</u></h4>


                                    <table class="table table-striped table-bordered" id="server-players">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div> <!-- /.col -->

                                <div class="col-sm-6">

                                    <h4 class="content-title"><u>Mods</u></h4>

                                    <table class="table table-striped table-bordered" id="server-mods">
                                        <thead>
                                        <tr>
                                            <th>Mod</th>
                                            <th style="width: 30%">Version</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Mod</th>
                                            <th style="width: 30%">Version</th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div> <!-- /.col -->

                            </div> <!-- /.row -->

                            </div>

                    </div>
                    <!-- /.portlet -->

                </div>
                <!-- /.container -->

            </div>
            <!-- .content -->
        </div>

    </div>

@stop