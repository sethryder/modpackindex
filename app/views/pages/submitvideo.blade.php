@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Submit Video / Playlist</u>
                        </h2>

                        <div class="portlet-body">

                            <p>Have or know of a Video / Playlist that should be included on the site? Let us know about
                                it! Fill
                                out the form below and we will get it added ASAP.</p>

                            <p><b>Note:</b> Currently we only support Youtube.</p>

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
                                    <strong>Submitted!</strong> We will get this video added ASAP!
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => action('StaticPagesController@postSubmitVideo'), 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('url','Video / Playlist URL') }}:
                                {{ Form::text('url', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('Type','Video / Playlist Type') }}:
                                {{ Form::select('type', $types, null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('modpack','Modpack') }} (if applicable):
                                {{ Form::select('modpack', ['' => 'Select Modpack'] + Modpack::orderBy('name')->lists('name', 'name'), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('mod','Mod') }} (if applicable):
                                {{ Form::select('mod', ['' => 'Select Mod'] + Mod::orderBy('name')->lists('name', 'name'), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('email','Your Email') }}:
                                {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'If we need to reach your or have questions.'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('comments','Comments') }}:
                                {{ Form::textarea('comments', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Any comments for us or anything we should know?'))}}
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