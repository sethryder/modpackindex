@extends('layouts.base')

@section('content')
 <div class="content">

    <div class="container">

      <div class="row">

        <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

          <div class="portlet">

            <h2 class="portlet-title">
              <u>Add A Mod</u>
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
                    <strong>Added!</strong> You may add another mod below or leave this page.
                </div> <!-- /.alert -->
            @endif

             {{ Form::open(array('url' => '/mod/add', 'class' => 'form parsley-form')) }}

                <div class="form-group">
                    {{ Form::label('name','Name') }}:
                    {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                </div> <!-- /.form-group -->

                <div class="form-group">
                  <label>Version(s) Supported</label>

                  @foreach ($versions as $version)
                  <div class="checkbox">
                  <label>
                    {{ Form::checkbox('versions[]', $version->id);  }}
                    {{ $version->name }}
                    </label>
                  </div> <!-- /.checkbox -->
                  @endforeach

                </div> <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::label('author','Author(s)') }}:
                    {{ Form::select('author[]', Author::lists('name', 'id'), null, array('multiple', 'class' => 'chosen-select form-control')) }}
                </div>

                <div class="form-group">
                    {{ Form::label('deck','Deck') }}:
                    {{ Form::text('deck', null, array('class' => 'form-control'))}}
                </div> <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::label('website','Website') }}:
                    {{ Form::text('website', null, array('class' => 'form-control'))}}
                </div> <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::label('download_link','Download Link') }}:
                    {{ Form::text('download_link', null, array('class' => 'form-control'))}}
                </div> <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::label('donate_link','Donate Link') }}:
                    {{ Form::text('donate_link', null, array('class' => 'form-control'))}}
                </div> <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::label('wiki_link','Wiki Link') }}:
                    {{ Form::text('wiki_link', null, array('class' => 'form-control'))}}
                </div> <!-- /.form-group -->

                <div class="form-group">
                    {{ Form::label('description','Description') }}:
                    {{ Form::textarea('description', null, array('class' => 'form-control'))}}
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