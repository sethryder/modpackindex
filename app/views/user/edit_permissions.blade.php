@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Editing {{ $user->username }}'s Permissions</u>
                        </h2>

                        <div class="portlet-body">

                            @if ( $errors->count() > 0 )
                                <div class="alert alert-danger">
                                    <p>The following errors have occurred:</p>

                                    <ul>
                                        @foreach( $errors->all() as $message )
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div> <!-- /.alert -->
                            @endif

                            @if (isset($success))
                                <div class="alert alert-success">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                                    <strong>Edited!</strong> You may perform more edits on this user or leave the page.
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => 'user/permissions/' . $user->id, 'class' => 'form parsley-form')) }}

                            @foreach ($available_permissions as $permission)
                                <div class="checkbox">
                                    <label>
                                        @if (in_array($permission->id, $selected_permissions))
                                            {{ Form::checkbox('selected_permissions[]', $permission->id, true);  }}
                                        @else
                                            {{ Form::checkbox('selected_permissions[]', $permission->id);  }}
                                        @endif
                                        {{ $permission->display_name }}
                                    </label>
                                </div> <!-- /.checkbox -->
                            @endforeach

                            {{ Form::submit('Edit', ['class' => 'btn btn-danger']) }}

                            {{ Form::close() }}

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