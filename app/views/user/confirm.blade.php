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

      </div> <!-- /.row -->

    </div> <!-- /.container -->

  </div> <!-- .content -->
</div>
@stop