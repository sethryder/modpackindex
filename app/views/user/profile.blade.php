@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                @include('user.menu', ['page' => 'profile']);

                <div class="col-md-9 col-sm-7">

                    <h3>{{{ $user_info['username'] }}}</h3>

                    <hr>

                    <ul class="icons-list">
                        @if ($user_info['real_name'])
                            <li><i class="icon-li fa fa-user"></i> {{{ $user_info['real_name'] }}}</li>
                        @endif
                        @if ($user_info['hide_email'] == 0)
                            <li><i class="icon-li fa fa-envelope"></i> {{{ $user_info['email'] }}}</li>
                        @endif
                        @if ($user_info['website'])
                            <li><i class="icon-li fa fa-globe"></i> <a
                                        href="{{{ $user_info['website'] }}}">{{{ $user_info['website'] }}}</a></li>
                        @endif
                        @if ($user_info['github'])
                            <li><i class="icon-li fa fa-github"></i> <a
                                        href="http://www.github.com/{{{ $user_info['github'] }}}">{{{ $user_info['github'] }}}</a>
                            </li>
                        @endif
                        @if ($user_info['location'])
                            <li><i class="icon-li fa fa-map-marker"></i> {{{ $user_info['location'] }}}</li>
                        @endif
                    </ul>

                    <br>

                    <p>{{{ $user_info['about_me'] }}}</p>
                    <hr>

                    <br class="visible-xs">
                    <br class="visible-xs">

                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->

            <br><br>

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop