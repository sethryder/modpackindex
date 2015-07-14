@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                @include('user.menu', ['page' => 'mods']);

                <div class="col-md-9 col-sm-7">

                    <h3>@if ($my_profile)My @endif Mods</h3>

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
                        @foreach ($mods as $mod)
                            <tr>
                                <td>{{{ $mod['name'] }}}</td>
                                <td>{{{ $mod['versions'] }}}</td>
                                <td><a href="/mod/{{{ $mod['slug'] }}}">View</a> @if($my_profile)| <a href="/mod/edit/{{ $mod['id'] }}">Edit</a>@endif</td>
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