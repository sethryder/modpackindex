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
                                    <strong>Added!</strong> You may add another server below or leave this page.
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => '/server/add', 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('name','Name') }}:
                                {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('server_address','Server Address') }}:
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

                            <div class="form-group">
                                {{ Form::label('modpack','Modpack') }}:
                                {{ Form::select('modpack', Modpack::lists('name', 'id'), null, array('class' => 'form-control')) }}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('tags','Tags') }}:
                                {{ Form::select('tags[]', ServerTag::lists('name', 'id'), null, array('multiple', 'class' => 'chosen-select form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('permissions','Server Permissions') }}:
                                {{ Form::select('permissions', $permissions, null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('country','Server Location') }}:
                                {{ Form::select('country', $countries, null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('deck','Short Description') }}:
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

                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('motd_hide', 1);  }}
                                        Hide MoTD
                                    </label>
                                </div>
                                <!-- /.checkbox -->

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