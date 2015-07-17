@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{{ $version }}} Modpacks</u>
                </h3>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered" id="table-1">
                        <thead>
                        <tr>
                            <th style="width: 20%">Name</th>
                            <th style="width: 10%">MC Version</th>
                            <th style="width: 36%">Description</th>
                            <th style="width: 15%">Creator(s)</th>
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

                </div>
                <!-- /.portlet-body -->

            </div>
            <!-- /.portlet -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop