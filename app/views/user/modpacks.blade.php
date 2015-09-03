@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                @include('user.menu', ['page' => 'modpacks']);

                <div class="col-md-9 col-sm-7">

                    <h3>@if ($my_profile)My @endif Modpacks</h3>

                    <hr>

                    <table class="table table-striped table-bordered" id="table-1">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th style="width: 30%">Version</th>
                            <th style="width: 30%">Link(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($modpacks as $modpack)
                            <tr>
                                <td>{{{ $modpack['name'] }}}</td>
                                <td>{{{ $modpack['version'] }}}</td>
                                <td><a href="{{ action('ModpackController@getModpack', [$modpack['version_slug'], $modpack['slug']]) }}">View</a> @if($my_profile)| <a href="{{ action('ModpackController@getEdit', [$modpack['id']]) }}">Edit</a>@endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Version</th>
                            <th>Link(s)</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->

            <br><br>

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop