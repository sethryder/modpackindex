@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-3 col-sm-5">

                    <div class="list-group">

                        <a href="/profile/{{ $user['username'] }}" class="list-group-item">
                            <i class="fa fa-user text-primary"></i> &nbsp;&nbsp;Profle

                            <i class="fa fa-chevron-right list-group-chevron"></i>
                        </a>

                        <a href="/profile/edit" class="list-group-item active">
                            <i class="fa fa-plus"></i> &nbsp;&nbsp;Edit Profile

                            <i class="fa fa-chevron-right list-group-chevron"></i>
                        </a>

                        <a href="/profile/edit/password" class="list-group-item">
                            <i class="fa fa-edit text-primary"></i> &nbsp;&nbsp;Change Password

                            <i class="fa fa-chevron-right list-group-chevron"></i>
                        </a>
                    </div> <!-- /.list-group -->



                </div> <!-- /.col -->



                <div class="col-md-9 col-sm-7">

                    <h3>Edit Profile</h3>

                    <hr>

                    <p><b>Note:</b> None of this information is required except for email. Only fill in what you would like to display on your profile.</p>

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

                    {{ Form::model($user_info, array('url' => '/profile/edit', 'class' => 'form parsley-form')) }}

                    <div class="form-group">
                        {{ Form::label('email','Email') }}:
                        {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('real_name','Real Name') }}:
                        {{ Form::text('real_name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('location','Location') }}:
                        {{ Form::text('location', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('website','Website') }}:
                        {{ Form::text('website', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('github','Github Username') }}:
                        {{ Form::text('github', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('about_me','About Me') }}:
                        {{ Form::textarea('about_me', null, array('class' => 'form-control', 'placeholder' => 'We currently only allow plain text in this field. We will be updating that in the future.'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                {{ Form::checkbox('hide_email', 1);  }}
                                Hide Email (recommended at this time)
                            </label>
                        </div> <!-- /.checkbox -->
                    </div> <!-- /.form-group -->


                    {{ Form::submit('Edit', ['class' => 'btn btn-danger']) }}

                    {{ Form::close() }}

                    <hr>

                    <br class="visible-xs">
                    <br class="visible-xs">

                </div> <!-- /.col -->


            </div> <!-- /.row -->

            <br><br>

        </div> <!-- /.container -->

    </div> <!-- .content -->
@stop