@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Submit Modpack</u>
                        </h2>

                        <div class="portlet-body">

                            <p>Have or know of a Modpack that should be included on the site? Let us know about it! Fill
                                out the form below and we will get it added ASAP.</p>

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
                                    <strong>Submitted!</strong> We will get this modpack added ASAP!
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => '/submit-modpack', 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('name','Modpack Name') }}*:
                                {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('creators_name', 'Creators Name(s)') }}*:
                                {{ Form::text('creators_name', null, array('class' => 'form-control'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('minecraft_version','Minecraft Version') }}*:
                                {{ Form::select('minecraft_version', MinecraftVersion::lists('name', 'name'), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('launcher','Launcher') }}*:
                                {{ Form::select('launcher', Launcher::lists('name', 'name'), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('website','Website / Forum Post') }}*:
                                {{ Form::text('website', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('modlist','Link To Mod List') }}*:
                                {{ Form::text('modlist', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Link to a list of mods in the modpack'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('packcode','Pack Code') }} (if applicable):
                                {{ Form::text('packcode', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('deck','Short Description') }}*:
                                {{ Form::text('deck', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('description','Full Description') }}:
                                {{ Form::textarea('description', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('comments','Comments') }}:
                                {{ Form::textarea('comments', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Any comments for us or anything we should know?'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('email','Your Email') }}:
                                {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'If we need to reach you or have questions.'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group" style="margin-left: auto; margin-right: auto; display: block;">
                                {{ Form::label('captcha','Are you a human?') }}
                                {{ Form::captcha(array('style' => 'margin-left: auto; margin-right: auto;'))}}
                            </div>

                            {{ Form::submit('Submit', ['class' => 'btn btn-danger']) }}

                            {{ Form::close() }}

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