@extends('layouts.base')

@section('content')
    <div class="container">

        <div class="account-wrapper">

            <div class="account-body">

                @if ( $errors->count() > 0 )
                    <div style="text-align: left" class="alert alert-danger">
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
                        <strong>Password Updated!</strong> You can login below.
                    </div> <!-- /.alert -->

                    {{ Form::open(array('url' => '/login', 'class' => 'form account-form')) }}

                    <div class="form-group">
                        {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Email'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Your Password'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group clearfix">
                        <div class="pull-left">
                            <label class="checkbox-inline">
                                {{ Form::checkbox('remember_me', 'true', false, array('tabindex' => 3)); }}
                                <small>Remember me</small>
                            </label>
                        </div>
                    </div> <!-- /.form-group -->


                    {{ Form::submit('Login', ['class' => 'btn btn-primary btn-block btn-lg']) }}

                    {{ Form::close() }}
                @else

                    <h3>Reset Password</h3>
                        <h3>for {{{ $username }}}</h3>

                    {{ Form::open(array('url' => '/reset/' . $token, 'class' => 'form account-form')) }}

                    <div class="form-group">
                        {{ Form::password('new_password', array('class' => 'form-control', 'placeholder' => 'New Password'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::password('confirm_password', array('class' => 'form-control', 'placeholder' => 'Confirm Password'))}}
                    </div> <!-- /.form-group -->

                    {{ Form::submit('Set New Password', ['class' => 'btn btn-primary btn-block btn-lg']) }}
                    {{ Form::close() }}

                @endif


            </div> <!-- /.account-body -->

            <div class="account-footer">
                <p>
                    Don't have an account? &nbsp;
                    <a href="/register" class="">Create an Account!</a>
                </p>
            </div> <!-- /.account-footer -->

        </div> <!-- /.account-wrapper -->
    </div>
@stop