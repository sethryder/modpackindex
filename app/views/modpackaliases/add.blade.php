@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Add A Modpack Alias</u>
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
                                    <strong>Added!</strong> You may add another Modpack Alias below.
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => action('ModpackAliasController@postAdd'), 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('alias','Alias') }}:
                                {{ Form::text('alias', null, ['class' => 'form-control', 'data-required' => 'true'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('modpack','Modpack') }}:
                                {{ Form::select('modpack', [0 => 'Select Modpack'] + Modpack::lists('name', 'id'), null, array('class' => 'form-control')) }}
                            </div>

                            {{ Form::submit('Add', ['class' => 'btn btn-danger']) }}

                            {{ Form::close() }}

                        </div>
                        <!-- /.portlet-body -->

                    </div>
                    <!-- /.portlet -->

                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->

@stop