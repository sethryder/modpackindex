@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>Compare Modpacks</u>
                </h3>

                <div class="portlet-body">
                    <p>Compare mods between two or more modpacks. Select the modpacks you are interested in below and we
                        will generate
                        a table comparing the mods in each pack.</p>
                </div>

                <div class="portlet-body" style="width: 50%">
                    <div class="form-group">
                        {{ Form::open(array('url' => action('ModpackController@postCompare'), 'class' => 'form parsley-form')) }}

                        {{ Form::label('modpacks','Modpacks') }}:
                        @if (isset($results))
                            {{ Form::select('modpacks[]', Modpack::orderBy('name')->lists('name', 'id'), $selected_modpacks, array('multiple', 'class' => 'chosen-select form-control',)) }}
                        @else
                            {{ Form::select('modpacks[]',  Modpack::orderBy('name')->lists('name', 'id'), null, array('multiple', 'class' => 'chosen-select form-control',)) }}
                        @endif

                    </div>
                    {{ Form::submit('Compare', ['class' => 'btn btn-danger']) }}

                </div>

                @if ($error)
                    <p>&nbsp;</p>
                    <div class="alert alert-danger" style="width: 50%">
                        <p>{{ $error }}</p>
                    </div> <!-- /.alert -->
                @endif

                @if ($results == true)
                    <p>&nbsp;</p>

                    <h3 class="portlet-title">
                        <u>Results</u>
                    </h3>


                    <div class="portlet-body">
                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                            <tr>
                                <th style="width: 20%">Mods</th>
                                @foreach($modpacks as $modpack)
                                    <th>{{ $modpack }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Mods</th>
                                @foreach($modpacks as $modpack)
                                    <th>{{ $modpack }}</th>
                                @endforeach
                            </tr>
                            </tfoot>
                        </table>
                        @endif

                    </div> <!-- /.portlet-body -->

            </div>
            <!-- /.portlet -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop