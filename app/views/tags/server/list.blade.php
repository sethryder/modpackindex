@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Server Tags</u>
                        </h2>

                        <div class="portlet-body">

                            <p class="pull-right"><a href="{{ action('ModpackTagController@getAdd') }}">Add</a></p>
                            <table class="table table-striped table-bordered" id="table-1">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th style="width: 30%">Link(s)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tags as $tag)
                                    <tr>
                                        <td>{{{ $tag['name'] }}}</td>
                                        <td><a href="{{ action('ModpackTagController@getEdit', [$tag['id']]) }}">Edit</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Link(s)</th>
                                </tr>
                                </tfoot>
                            </table>

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