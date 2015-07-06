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

                        <a href="/profile/edit" class="list-group-item">
                            <i class="fa fa-plus text-primary"></i> &nbsp;&nbsp;Edit Profile

                            <i class="fa fa-chevron-right list-group-chevron"></i>
                        </a>

                        <a href="/profile/edit/password" class="list-group-item active">
                            <i class="fa fa-edit"></i> &nbsp;&nbsp;Change Password

                            <i class="fa fa-chevron-right list-group-chevron"></i>
                        </a>
                    </div>
                    <!-- /.list-group -->


                </div>
                <!-- /.col -->


                <div class="col-md-9 col-sm-7">

                    <h3>Change Password</h3>

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
                            <strong>Password Updated!</strong>
                        </div> <!-- /.alert -->
                    @endif

                    {{ Form::open(array('url' => '/profile/edit/password', 'class' => 'form parsley-form')) }}

                    <div class="form-group">
                        {{ Form::label('current_password','Current Password') }}:
                        {{ Form::password('current_password', array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('new_password','New Password') }}:
                        {{ Form::password('new_password', array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('confirm_password','Confirm Password') }}:
                        {{ Form::password('confirm_password', array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->


                    {{ Form::submit('Change', ['class' => 'btn btn-danger']) }}

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