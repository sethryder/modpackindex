@extends('layouts.base')

@section('content')
    <div class="container">

        <div class="account-wrapper">

            <div class="account-body">

                @if (!$error)
                    <div class="alert alert-success">
                        <strong>Server confirmed!</strong> You can view your server <a href="/server/{{{ $server->id }}}/{{{ $server->slug }}}">here</a>.
                    </div> <!-- /.alert -->
                @else
                    <div class="alert alert-danger">
                        <strong>Error!</strong> {{{ $error }}}
                    </div> <!-- /.alert -->

                    <p>If you feel this is an error, please <a href="{{ action('StaticPagesController@getContact') }}">contact us</a>.</p>
                @endif


            </div>
            <!-- /.account-body -->

        </div>
        <!-- /.account-wrapper -->
    </div>
@stop