@extends('layouts.base')

@section('content')
 <div class="content">

    <div class="container">

      <div class="row">

        <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

          <div class="portlet">

            <h2 class="portlet-title">
              <u>Edit An Author</u>
            </h2>

            <div class="portlet-body">

            @if ( $errors->count() > 0 )
            <div class="alert alert-danger">
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
                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                <strong>Added!</strong> You may add another author below or leave this page.
            </div> <!-- /.alert -->
            @endif

                {{ Form::model($author, array('url' => '/author/add', 'class' => 'form parsley-form')) }}

                    <div class="form-group">
                        {{ Form::label('name','Name') }}:
                        {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('website','Website') }}:
                        {{ Form::text('website', null, array('class' => 'form-control'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('donate_link','Donate Link') }}:
                        {{ Form::text('donate_link', null, array('class' => 'form-control'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('bio','Bio') }}:
                        {{ Form::textarea('bio', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div> <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('slug','Slug') }}:
                        {{ Form::text('slug', null, array('class' => 'form-control'))}}
                    </div> <!-- /.form-group -->

                    {{ Form::submit('Add', ['class' => 'btn btn-danger']) }}

                {{ Form::close() }}

            </div> <!-- /.portlet-body -->

          </div> <!-- /.portlet -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->

    </div> <!-- /.container -->



  </div> <!-- .content -->

@stop