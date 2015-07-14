@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                @include('user.menu', ['page' => 'password']);

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