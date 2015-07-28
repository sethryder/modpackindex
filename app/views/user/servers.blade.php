@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                @include('user.menu', ['page' => 'servers']);

                <div class="col-md-9 col-sm-7">

                    <h3>@if ($my_profile)My @endif Servers</h3>

                    <hr>

                    @if (count($active_servers))

                    <h4>Active Servers</h4>

                    <table class="table table-striped table-bordered" id="table-1">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th style="width: 30%">Link(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($active_servers as $server)
                            <tr>
                                <td>{{{ $server->name }}}</td>
                                <td><a href="/server/{{{ $server->id }}}/{{{ $server->slug }}}">View</a> @if($my_profile)| <a href="/server/edit/{{ $server->id }}">Edit</a>@endif</td>
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

                    @endif


                    @if (count($inactive_servers))

                        <h4>Inactive Servers</h4>

                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th style="width: 30%">Link(s)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($inactive_servers as $server)
                                <tr>
                                    <td>{{{ $server->name }}}</td>
                                    <td><a href="/server/{{{ $server->id }}}/{{{ $server->slug }}}">View</a> @if($my_profile)| <a href="/server/edit/{{ $server->id }}">Edit</a>@endif</td>
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

                    @endif

                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->

            <br><br>

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop