@extends('layouts.base')

@section('content')
<div class="content">

    <div class="container">

      <div class="error-container">

        <div class="error-code">
        500
        </div> <!-- /.error-code -->

        <div class="error-details">
          <h4>There was a problem serving the requested page.</h4>

          <br>

          <p><strong>What should I do:</strong></p>

          <ul class="icons-list">
            <li>
              <i class="icon-li fa fa-check-square-o"></i>
              you can try refreshing the page, the problem may be temporary
            </li>
            <li>
              <i class="icon-li fa fa-check-square-o"></i>
              if you entered the url by hand, double check that it is correct
            </li>
            <li>
              <i class="icon-li fa fa-check-square-o"></i>
              Nothing! we've been notified of the problem and will do our best to make sure it doesn't happen again!
            </li>
          </ul>
        </div> <!-- /.error-details -->

      </div> <!-- /.error -->

    </div> <!-- /.container -->

  </div> <!-- .content -->

@stop