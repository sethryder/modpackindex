@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Add Server</u>
                        </h2>

                        <div class="portlet-body">

                            <p>Add your server below. Your server will need to be online at the time of adding it.
                            If the server goes offline and we are unable to reach it after many tries it will automatically
                            be deactivated.</p>

                            @if (!Auth::check())
                            <p>Since you are not logged in, we will send you an email to activate your server and a special
                            URL so that you may edit your server after it is added. If you will be adding multiple servers
                            we highly suggest creating a user account <a href="/user/register">here</a> to make it easier
                            to manage.</p>
                            @endif

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
                                @if (Auth::check())
                                    <div class="alert alert-success">
                                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                                        <strong>Added!</strong> You may add another server below or leave this page.
                                    </div> <!-- /.alert -->
                                @else
                                    <div class="alert alert-success">
                                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                                        <strong>Added!</strong> Check your email for the server confirmation. Your server
                                        will not display on the server listings until activated! At times it can take up
                                        to an hour for the email to arrive.
                                    </div> <!-- /.alert -->
                                @endif
                            @endif

                            {{ Form::open(array('url' => '/server/add', 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('name','Server Name') }}*:
                                {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('server_address','Server Address') }}*:
                                {{ Form::text('server_address', null, array('class' => 'form-control', 'placeholder' => 'mc.modpackindex.com:27016'))}}

                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('server_address_hide', 1);  }}
                                        Hide Server Address
                                    </label>
                                </div>
                                <!-- /.checkbox -->
                            </div>
                            <!-- /.form-group -->

                            @if (!Auth::check())
                                <div class="form-group">
                                    {{ Form::label('email','Email') }}*:
                                    {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                                    <p class="pull-right">Required so you can activate and edit your server.</p>
                                </div>
                                <!-- /.form-group -->
                            @endif

                            <div class="form-group">
                                {{ Form::label('modpack','Modpack') }}*:
                                {{ Form::select('modpack', Modpack::orderBy('name')->lists('name', 'id'), null, array('class' => 'form-control')) }}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('tags','Tags') }}*:
                                {{ Form::select('tags[]', ServerTag::orderBy('name')->lists('name', 'id'), null, array('multiple', 'class' => 'chosen-select form-control')) }}
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

                            <div class="form-group date">
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
                            @if (Auth::check())
                                <div class="checkbox">
                                        <label>
                                            {{ Form::checkbox('active', 1, true);  }}
                                            Active
                                        </label>
                                    </div>
                                    <!-- /.checkbox -->
                            @endif
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

                                @if (!Auth::check())
                                    <div class="form-group">
                                        {{ Form::label('captcha','Are you a robot?') }}
                                        {{ Form::captcha()}}
                                    </div>
                                @endif
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

                            {{ Form::submit('Add', ['class' => 'btn btn-danger']) }}

                            {{ Form::close() }}

                            <br />

                            <p>By adding this server you agree that the server does not violate the
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