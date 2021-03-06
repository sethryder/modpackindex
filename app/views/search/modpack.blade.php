@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>Modpack Finder</u>

                </h3>

                <div class="portlet-body">
                    <p>Find the right Modpack for you. You can search by Tags/Type, Mods, or both!</p>
                </div>

                <p>&nbsp;</p>

                <h4 class="portlet-title">
                    <u>What are you looking for?</u>
                </h4>

                <div class="portlet-body" style="width: 50%">
                    {{ Form::open(array('url' => action('SearchController@postModpackSearch'), 'class' => 'form parsley-form')) }}
                    <div class="form-group">
                        {{ Form::label('mc_version','Minecraft Version') }}:
                        {{ Form::select('mc_version', ['all' => 'All'] + $version_select, $selected_version, array('class' => 'form-control from-post')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('tags','Tags / Type') }}:
                        {{ Form::select('tags[]', ModpackTag::orderBy('name')->lists('name', 'slug'), $selected_tags, array('multiple', 'class' => 'chosen-select form-control')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('mods','Mods') }}:
                        @if (isset($results))
                            @if($url_version == 'all')
                                {{ Form::select('mods[]', $mods, $selected_mods, array('multiple', 'disabled', 'data-placeholder' => 'Select a specific Minecraft version to search with mods', 'class' => 'chosen-select form-control')) }}
                            @else
                                {{ Form::select('mods[]', $mods, $selected_mods, array('multiple', 'data-placeholder' => 'Select Mods', 'class' => 'chosen-select form-control')) }}
                            @endif
                        @endif
                    </div>
                    {{ Form::submit('Search', ['class' => 'btn btn-danger']) }}

                    {{ Form::close() }}
                </div>

                @if ($results)
                    <p>&nbsp;</p>
                    <h4 class="portlet-title">
                        <u>Results</u>
                    </h4>

                    <div class="portlet-body">
                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                            <tr>
                                <th style="width: 20%">Name</th>
                                <th style="width: 10%">MC Version</th>
                                <th style="width: 36%">Description</th>
                                <th style="width: 15%">Creators(s)</th>
                                <th style="width: 1%"></th>
                                <th style="width: 18%">Link(s)</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>MC Version</th>
                                <th>Description</th>
                                <th>Creators(s)</th>
                                <th></th>
                                <th>Link(s)</th>

                            </tr>
                            </tfoot>
                        </table>

                    </div> <!-- /.portlet-body -->
                @endif

            </div>
            <!-- /.portlet -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop