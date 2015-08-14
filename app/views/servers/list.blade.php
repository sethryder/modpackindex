@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u><a class="accordion-toggle filter-toggle" id="filter-toggle" data-toggle="collapse" data-parent=".accordion" href="#filters">Filters (click to toggle)</a></u>
                </h3>
                <div id="filters" class="accordion-body panel-collapse collapse">
                    <div class="portlet-body">
                        {{ Form::open(array('url' => '/servers/', 'class' => 'form parsley-form')) }}

                        <div class="form-group">
                            {{ Form::label('modpack','Modpack'  ) }}:
                            {{ Form::select('modpack', ['any' => 'Any'] + Modpack::orderBy('name')->lists('name', 'slug'), $selected_modpack, array('class' => 'server-filter form-control')) }}</div>
                        </div>

                        <div class="form-group server-filter-tags">
                            {{ Form::label('tags','Tags') }}:
                            {{ Form::select('tags[]', ServerTag::orderBy('name')->lists('name', 'slug'), $selected_tags, array('multiple', 'class' => 'chosen-select form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('country','Country') }}:
                            {{ Form::select('country', ['all' => 'All'] + $countries, $selected_country, array('class' => 'server-filter form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('permission','Permission') }}:
                            {{ Form::select('permission', ['any' => 'Any'] + $permissions, $selected_permission, array('class' => 'server-filter form-control')) }}
                        </div>

                        {{ Form::submit('Filter', ['class' => 'btn btn-danger']) }}

                        {{ Form::close() }}

                        <br />

                    </div>
                </div>


                <h3 class="portlet-title">
                    <u>{{ $modpack_name or "All" }} Servers</u>
                    <div class="pull-right"> <a href="/server/add" class="btn btn-primary" role="button">Add Server</a></div>
                </h3>

                <div class="portlet-body">

                    <p>{{{ $display_line }}}</p>

                    <table class="table table-striped table-bordered" id="servers-table">
                        <thead>
                        <tr>
                            <th style="width: 7%"></th>
                            <th style="width: 20%">Name</th>
                            <th style="width: 15%">Modpack</th>
                            <th style="width: 15%">Server Address</th>
                            <th style="width: 1%">Players</th>
                            <th style="width: 42%">Description</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Modpack</th>
                            <th>Server Address</th>
                            <th>Players</th>
                            <th>Description</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
                <!-- /.portlet-body -->

            </div>
            <!-- /.portlet -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop