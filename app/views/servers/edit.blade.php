@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Edit Server</u>
                        </h2>

                        <div class="portlet-body">

                            <p>Edit your server below. Your server will need to be online at the time of edit.
                                If the server goes offline and we are unable to reach it after many tries it will automatically
                                be deactivated.</p>

                            <p><b>Note</b>: Required fields have a (*) next to them.</p>

                            <p>&nbsp;</p>

                            @if ( $errors->count() > 0 )
                                <div class="alert alert-danger">
                                    <p>The following errors have occurred:</p>

                                    <ul>
                                        @foreach( $errors->all() as $message )
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div> <!-- /.alert -->
                            @endif

                            @if (isset($success))
                                <div class="alert alert-success">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                                    <strong>Edited!</strong> You may more edits below or return to the server's detail
                                    page <a href="/server/{{{ $server->id }}}/{{{ $server->slug }}}">here</a>.
                                </div> <!-- /.alert -->
                            @endif

                                {{ Form::model($server, array('url' => '/server/edit/'.$server->id, 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('name','Name') }}*:
                                {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('server_address','Server Address') }}*:
                                {{ Form::text('server_address', $server->ip_host . ':' . $server->port, array('class' => 'form-control', 'placeholder' => 'mc.modpackindex.com:27016'))}}

                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('server_address_hide', 1);  }}
                                        Hide Server Address
                                    </label>
                                </div>
                                <!-- /.checkbox -->
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('modpack','Modpack') }}*:
                                {{ Form::select('modpack', Modpack::orderBy('name')->lists('name', 'id'), $server->modpack_id, array('class' => 'form-control')) }}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('selected_tags','Tags') }}*:
                                {{ Form::select('selected_tags[]', ServerTag::orderBy('name')->lists('name', 'id'), $selected_tags, array('multiple', 'class' => 'chosen-select form-control')) }}
                                <p class="pull-right">Are we missing a tag? <a href="/contact" target="_blank">Let us know</a>.</p>
                            </div>

                            <div class="form-group">
                                {{ Form::label('permissions','Server Permissions') }}*:
                                {{ Form::select('permissions', $permissions, null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('country','Server Location') }}*:
                                {{ Form::select('country', $countries, null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('deck','Short Description') }}*:
                                {{ Form::text('deck', null, array('class' => 'form-control'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('website','Website') }}:
                                {{ Form::text('website', null, array('class' => 'form-control'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('application_url','Whitelist / Greylist Application') }}:
                                {{ Form::text('application_url', null, array('class' => 'form-control'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group" id="datepicker">
                                {{ Form::label('last_world_reset','Last World Reset') }}:
                                {{ Form::text('last_world_reset', null, array('class' => 'form-control', 'data-date-end-date' => '0d'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group" id="datepicker">
                                {{ Form::label('next_world_reset','Next World Reset') }}:
                                {{ Form::text('next_world_reset', null, array('class' => 'form-control', 'data-date-start-date' => '0d'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('description','Description') }}:
                                {{ Form::textarea('description', null, array('class' => 'form-control'))}}
                                <p class="pull-right">We support <a href="https://help.github.com/articles/github-flavored-markdown/" target="_blank">Github Flavored Markdown</a>.</p>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('active', 1, true);  }}
                                        Active
                                    </label>
                                </div>
                                <!-- /.checkbox -->

                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('email_alerts', 1, true);  }}
                                        Email me if my server is deactivated
                                    </label>
                                </div>
                                <!-- /.checkbox -->

                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('player_list_hide', 1);  }}
                                        Hide Player List
                                    </label>
                                </div>
                                <!-- /.checkbox -->

{{--                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('motd_hide', 1);  }}
                                        Hide MoTD
                                    </label>
                                </div>
                                <!-- /.checkbox -->--}}

                            </div>
                            <!-- /.form-group -->

                            {{ Form::submit('Edit', ['class' => 'btn btn-danger']) }}

                            {{ Form::close() }}

                            <br />

                            <p>By editing this server you agree that the server does not violate the
                                <a href="https://help.mojang.com/customer/portal/articles/1590522-minecraft-commercial-use-guidelines" target="_blank">Minecraft EULA</a>.</p>

                        </div>
                        <!-- /.portlet-body -->

                    </div>
                    <!-- /.portlet -->

                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->


    </div> <!-- .content -->

@stop