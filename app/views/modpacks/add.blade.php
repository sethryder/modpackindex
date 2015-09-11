@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Add A {{ $version  }} Modpack</u>
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
                                    <strong>Added!</strong> You may add another modpack below or leave this page.
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(['url' => action('ModpackController@postAdd', [$url_version]), 'class' => 'form parsley-form']) }}

                            <div class="form-group">
                                {{ Form::label('name','Name') }}:
                                {{ Form::text('name', null, ['class' => 'form-control', 'data-required' => 'true'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('launcher','Launcher') }}:
                                {{ Form::select('launcher', Launcher::orderBy('name')->lists('name', 'id'), null, ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('mods','Mods') }}:
                                {{ Form::select('mods[]', $mods, null, array('multiple', 'class' => 'chosen-select form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('tags','Tags') }}:
                                {{ Form::select('tags[]', ModpackTag::orderBy('name')->lists('name', 'id'), null, array('multiple', 'class' => 'chosen-select form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('creator','Creator(s)') }}:
                                {{ Form::select('creators[]', Creator::orderBy('name')->lists('name', 'id'), null, array('multiple', 'class' => 'chosen-select form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('deck','Deck') }}:
                                {{ Form::text('deck', null, ['class' => 'form-control'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('website','Website') }}:
                                {{ Form::text('website', null, ['class' => 'form-control'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('download_link','Download Link') }}:
                                {{ Form::text('download_link', null, ['class' => 'form-control'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('donate_link','Donate Link') }}:
                                {{ Form::text('donate_link', null, ['class' => 'form-control'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('wiki_link','Wiki Link') }}:
                                {{ Form::text('wiki_link', null, ['class' => 'form-control'])}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('description','Description') }}:
                                {{ Form::textarea('description', null, ['class' => 'form-control'])}}
                                <p class="pull-right">We support <a
                                            href="https://help.github.com/articles/github-flavored-markdown/"
                                            target="_blank">Github Flavored Markdown</a>.</p>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('sequel_modpack','Sequel / Replacement Modpack') }}*:
                                {{ Form::select('sequel_modpack', Modpack::orderBy('name')->lists('name', 'id'), null, array('class' => 'form-control')) }}

                                <!-- /.checkbox -->
                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('is_deprecated', 1);  }}
                                        Deprecated
                                    </label>
                                </div>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('slug','Slug') }}:
                                {{ Form::text('slug', null, ['class' => 'form-control'])}}
                            </div>
                            <!-- /.form-group -->

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