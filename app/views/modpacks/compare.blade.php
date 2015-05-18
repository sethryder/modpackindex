@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>Compare Modpacks</u>
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
                            <th>Mod</th>
                            @foreach($modpacks as $modpack)
                                <th>{{ $modpack }}</th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>

                </div> <!-- /.portlet-body -->

            </div> <!-- /.portlet -->

        </div> <!-- /.container -->

    </div> <!-- .content -->
@stop