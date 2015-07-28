@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{ $server['description'] }} ( {{ $server['version']['name'] }} )</u>
                </h3>

                <div class="portlet-body">
                    A description that the user enters will go here, unless its a manual query.
                </div>

                <p>&nbsp;</p>

                <div class="row">

                    <div class="col-md-6">

                        <h3 class="portlet-title">
                            <u>Server Mods</u>
                        </h3>


                        <div class="portlet-body">
                            <table class="table table-striped table-bordered" id="table-1">
                                <thead>
                                <tr>
                                    <th>Mod</th>
                                    <th>Version</th>
                                </tr>
                                </thead>
                                @foreach($server['modinfo']['modList'] as $mod)
                                    <tr>
                                        <td>{{ $mod['modid'] }}</td>
                                        <td>{{ $mod['version'] }}</td>
                                    </tr>
                                @endforeach
                                <tfoot>
                                <tr>
                                    <th>Mod</th>
                                    <th>Version</th>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                        <!-- /.portlet-body -->

                    </div>

                    <div class="col-md-6">

                        <h3 class="portlet-title">
                            <u>Players ({{ $server['players']['online'] }} / {{ $server['players']['max'] }})</u>
                        </h3>

                        <div class="portlet-body">
                            <table class="table table-striped table-bordered" id="table-1">
                                <thead>
                                <tr>
                                    <th>Player</th>
                                </tr>
                                </thead>
                                @foreach($server['players']['sample'] as $player)
                                    <tr>
                                        <td>{{ $player['name'] }}</td>
                                    </tr>
                                @endforeach
                                <tfoot>
                                <tr>
                                    <th>Player</th>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                        <!-- /.portlet-body -->

                    </div>


                </div>
                <!-- /.portlet -->

            </div>
            <!-- /.container -->

        </div>
        <!-- .content -->

    </div>
@stop