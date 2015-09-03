@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Import a Mod</u>
                        </h2>

                        <p>You can import most information for a mod from <a href="http://modlist.mcf.li/">MCF
                                Modlist</a>, <a href="http://bot.notenoughmods.com/">Not Enough Mods</a>, or from the <a
                                    href="https://github.com/MinecraftForge/FML/wiki/FML-mod-information-file">mcmod.info</a>
                            file that is included with most
                            mods.</p>

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
                                    <strong>Imported!</strong> You may perform another import below or leave this page.
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => action('ImportController@postStartImport'), 'class' => 'form parsley-form', 'files' => true)) }}

                            <div class="form-group">
                                {{ Form::label('import_nem','Not Enough Mods') }}:
                                {{ Form::select('import_nem', $nem_mods_array, null, array('class' => 'form-control')) }}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('import_nem','MCF Modlist') }}:
                                {{ Form::select('import_mcf', $mcf_mods_array, null, array('class' => 'form-control')) }}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('import_file','File') }}:
                                {{ Form::file('import_file', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('url','URL') }}:
                                {{ Form::text('url', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('json','JSON') }}:
                                {{ Form::textarea('json', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            {{ Form::submit('Import', ['class' => 'btn btn-danger']) }}

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