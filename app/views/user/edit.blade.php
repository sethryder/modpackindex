@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                @include('user.menu', ['page' => 'edit']);

                <div class="col-md-9 col-sm-7">

                    <h3>Edit Profile</h3>

                    <hr>

                    <p><b>Note:</b> None of this information is required except for email. Only fill in what you would
                        like to display on your profile.</p>

                    <hr>

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
                            <strong>Updated!</strong> You may perform more edits or leave this page.
                        </div> <!-- /.alert -->
                    @endif

                    {{ Form::model($user_info, array('url' => action('UserController@postEditProfile'), 'class' => 'form parsley-form')) }}

                    <div class="form-group">
                        {{ Form::label('email','Email') }}:
                        {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('real_name','Real Name') }}:
                        {{ Form::text('real_name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('location','Location') }}:
                        {{ Form::text('location', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('website','Website') }}:
                        {{ Form::text('website', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('twitter','Twitter Username') }}:
                        {{ Form::text('twitter', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('github','Github Username') }}:
                        {{ Form::text('github', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('about_me','About Me') }}:
                        {{ Form::textarea('about_me', null, array('class' => 'form-control'))}}
                        <p class="pull-right">We support <a href="https://help.github.com/articles/github-flavored-markdown/" target="_blank">Github Flavored Markdown</a>.</p>
                    </div>
                    <!-- /.form-group -->


                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                {{ Form::checkbox('hide_email', 1);  }}
                                Hide Email
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                {{ Form::checkbox('hide_mods_modpacks', 1);  }}
                                Hide my Mods/Modpacks from my profile
                            </label>
                        </div>
                        <!-- /.checkbox -->
                    </div>
                    <!-- /.form-group -->


                    {{ Form::submit('Edit', ['class' => 'btn btn-danger']) }}

                    {{ Form::close() }}

                    <hr>

                    <br class="visible-xs">
                    <br class="visible-xs">

                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->

            <br><br>

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop