@extends('layouts.base')

@section('content')
<div class="container">

  <div class="account-wrapper">

    <div class="account-body">

      <h3>Welcome back.</h3>

      <form class="form account-form" method="POST" action="./index.html">

        <div class="form-group">
          <label for="login-username" class="placeholder-hidden">Username</label>
          <input type="text" class="form-control" id="login-username" placeholder="Username" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="login-password" class="placeholder-hidden">Password</label>
          <input type="password" class="form-control" id="login-password" placeholder="Password" tabindex="2">
        </div> <!-- /.form-group -->

        <div class="form-group clearfix">
          <div class="pull-left">
            <label class="checkbox-inline">
            <input type="checkbox" class="" value="" tabindex="3"> <small>Remember me</small>
            </label>
          </div>

          <div class="pull-right">
            <small><a href="./account-forgot.html">Forgot Password?</a></small>
          </div>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">
            Signin &nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->

      </form>


    </div> <!-- /.account-body -->

    <div class="account-footer">
      <p>
      Don't have an account? &nbsp;
      <a href="./account-signup.html" class="">Create an Account!</a>
      </p>
    </div> <!-- /.account-footer -->

  </div> <!-- /.account-wrapper -->
</div>
@stop