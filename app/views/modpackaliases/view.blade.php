@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <h3 class="portlet-title">
                        <u>{{{ $modpack->name }}} ({{{ $modpack->version->name  }}}) Aliases</u>
                    </h3>

                    <div style="text-align: center;"><h4><a href="/modpack-alias/add">Add</a></h4></div>

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Alias</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($aliases as $alias)
                                <tr>
                                    <td class="valign-middle">{{{ $alias->alias }}}</td>
                                    <td class="text-center valign-middle">
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></button>
                                        &nbsp;
                                        <button class="btn btn-xs btn-secondary"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                    <!-- /.table-responsive -->


                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
    </div>
@stop