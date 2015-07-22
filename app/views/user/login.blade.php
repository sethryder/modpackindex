@extends('layouts.base')

@section('content')
    <div class="container">
        <style>
            .g-recaptcha {
                width: 300px;
                margin-left: auto;
                margin-right: auto;
            }
        </style>

        <div class="account-wrapper">

            <div class="account-body">

                @if ( $errors->count() > 0 )
                    <div style="text-align: left" class="alert alert-danger">
                        <p>The following errors have occurred:</p>

                        <ul>
                            @foreach( $errors->all() as $message )
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>                    </div>
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

                @if ($return)
                    {{ Form::hidden('return', $return) }}
                @endif

                @if ($use_captcha)
                <div class="form-group">
                    {{ Form::captcha()}}
                </div>
                @endif

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