@extends('layouts.base')

@section('content')
<div class="container">

  <div class="account-wrapper">

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

    <div class="account-body">

      <h3>Register with Modpack Index</h3>

      <!-- <h5></h5> -->

       {{ Form::open(array('url' => '/register', 'class' => 'form parsley-form')) }}

          <div class="form-group">
              {{ Form::text('username', null, array('class' => 'form-control', 'data-required' => 'true', 'placeholder' => 'Your Username'))}}
          </div> <!-- /.form-group -->

          <div class="form-group">
              {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Your Email'))}}
          </div> <!-- /.form-group -->

          <div class="form-group">
              {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Your Password'))}}
          </div> <!-- /.form-group -->

          <div class="form-group">
              {{ Form::password('confirm_password', array('class' => 'form-control', 'placeholder' => 'Confirm Your Password'))}}
          </div> <!-- /.form-group -->

          <div class="form-group" style="margin-left: auto; margin-right: auto; display: block;">
            {{ Form::captcha(array('style' => 'margin-left: auto; margin-right: auto;'))}}
          </div>

       {{ Form::submit('Register!', ['class' => 'btn btn-secondary btn-block btn-lg']) }}

      {{ Form::close() }}

    </div> <!-- /.account-body -->

    <div class="account-footer">
      <p>
      Already have an account? &nbsp;
      <a href="/login" class="">Login to your Account!</a>
      </p>
    </div> <!-- /.account-footer -->

  </div> <!-- /.account-wrapper -->
</div>
@stop