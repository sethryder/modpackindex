@extends('layouts.base')

@section('content')
    <div class="container">

        <div class="account-wrapper">

            <div class="account-body">

                @if ( $errors->count() > 0 )
                    <div style="text-align: left" class="alert alert-danger">
                        <p>Unable to login with the provided credentials.</p>
                    </div>
                @endif

                <h3>Welcome back.</h3>

                {{ Form::open(array('url' => '/login', 'class' => 'form account-form')) }}

                <div class="form-group">
                    {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Email'))}}
                </div>
                <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Your Password'))}}
                </div>
                <!-- /.form-group -->

                <div class="form-group clearfix">
                    <div class="pull-left">
                        <label class="checkbox-inline">
                            {{ Form::checkbox('remember_me', 'true', false, array('tabindex' => 3)); }}
                            <small>Remember me</small>
                        </label>
                    </div>

                    <div class="pull-right">
                        <small><a href="/forgot">Forgot Password?</a></small>
                    </div>
                </div>
                <!-- /.form-group -->


                {{ Form::submit('Login', ['class' => 'btn btn-primary btn-block btn-lg']) }}

                {{ Form::close() }}

            </div>
            <!-- /.account-body -->

            <div class="account-footer">
                <p>
                    Don't have an account? &nbsp;
                    <a href="/register" class="">Create an Account!</a>
                </p>
            </div>
            <!-- /.account-footer -->

        </div>
        <!-- /.account-wrapper -->
    </div>
@stop