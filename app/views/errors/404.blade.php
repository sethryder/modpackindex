@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

          <div class="error-container">

            <div class="error-code">
            404
            </div> <!-- /.error-code -->

            <div class="error-details">

              <h4>Oops, <span class="text-primary">You're lost</span>.</h4>

              <p>We can not find the page you're looking for. Is there a typo in the url? If you followed a valid link
              please let us know <a href="/contact">here</a>.</p>

            </div> <!-- /.error-details -->

          </div> <!-- /.error -->

        </div> <!-- /.container -->

      </div> <!-- .content -->

    </div> <!-- /#wrapper -->
@stop