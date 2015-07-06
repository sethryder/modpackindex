@extends('layouts.base')

@section('content')
    <div class="container">

        <div class="account-wrapper">

            <div class="account-body">

                @if ($confirmed)
                    @if (!$error)
                        <div class="alert alert-success">
                            <strong>Account confirmed!</strong> You can login below.
                        </div> <!-- /.alert -->
                    @else
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Your account has already been activated. You can login below.
                        </div> <!-- /.alert -->
                    @endif


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

                        <div class="pull-right">
                            <small><a href="/forgot">Forgot Password?</a></small>
                        </div>
                    </div> <!-- /.form-group -->


                    {{ Form::submit('Login', ['class' => 'btn btn-primary btn-block btn-lg']) }}

                    {{ Form::close() }}
                @else
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Unable to confirm your account.
                    </div> <!-- /.alert -->

                    <p>If you feel this is an error, please <a href="/contact">contact us</a>.</p>
                @endif


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


@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-8">

                    @if ($confirmed)
                        <h2 class="">Your account has been confirmed. Thank you!</h2>
                    @else
                        <h2 class="">Unable to confirm your account.</h2>

                        <h4>If you feel like this is a mistake please contact us.</h4>
                    @endif
                    <br>

                </div>
                <!-- /.row -->

            </div>
            <!-- /.container -->

        </div>
        <!-- .content -->
    </div>
@stop