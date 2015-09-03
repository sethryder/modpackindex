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
                        </ul>
                    </div> <!-- /.alert -->
                @endif

                @if (isset($success))
                    <div class="alert alert-success">
                        <strong>Success!</strong> We have sent you instructions on how to reset your password to the
                        email you provided.
                    </div> <!-- /.alert -->
                @endif

                <h2>Password Reset</h2>

                <h5>We'll email you instructions on how to reset your password.</h5>

                {{ Form::open(array('url' => '/forgot', 'class' => 'form account-form')) }}

                <div class="form-group">
                    {{ Form::text('email', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Email'))}}
                </div>
                <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::captcha()}}
                </div>


                {{ Form::submit('Reset Password', ['class' => 'btn btn-secondary btn-block btn-lg']) }}

                {{ Form::close() }}


            </div>
            <!-- /.account-body -->

            <div class="account-footer">
                <p>
                    Don't have an account? &nbsp;
                    <a href="{{ action('UserController@getRegister') }}" class="">Create an Account!</a>
                </p>
            </div>
            <!-- /.account-footer -->

        </div>
        <!-- /.account-wrapper -->
    </div>
@stop