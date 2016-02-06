<div id="wrapper">
    <header class="navbar navbar-inverse" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-cog"></i>
                </button>

                <a href="{{ route('index') }}" class="navbar-brand">
                    Modpack Index
                </a>
            </div>
            <!-- /.navbar-header -->

            <nav class="collapse navbar-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    {{--<li>--}}
                    {{--<a href="/about">About</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="{{ action('StaticPagesController@getContact') }}">Contact</a>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown navbar-profile">--}}
                    {{--<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">--}}
                    {{--Submit --}}
                    {{--<i class="fa fa-caret-down"></i>--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu" role="menu">--}}

                    {{--<li>--}}
                    {{--<a href="/submit-modpack">--}}
                    {{--<i class="fa fa-gear"></i>--}}
                    {{--Modpack--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/submit-video">--}}
                    {{--<i class="fa fa-youtube-play"></i>--}}
                    {{--Video / Playlist--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--</ul>--}}

                    {{--</li>--}}

                    @if (Auth::check())
                        <li class="dropdown navbar-profile">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                                Submit
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li>
                                    <a href="{{ action('StaticPagesController@getSubmitModpack') }}">
                                        <i class="fa fa-gear"></i>
                                        Modpack
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ action('ServerController@getAdd') }}">
                                        <i class="fa fa-cloud"></i>
                                        Server
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ action('StaticPagesController@getSubmitVideo') }}">
                                        <i class="fa fa-youtube-play"></i>
                                        Video / Playlist
                                    </a>
                                </li>

                            </ul>

                        </li>
                        <li class="dropdown navbar-profile">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                                {{{ Auth::user()->username }}}
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li>
                                    <a href="{{ action('UserController@getProfile', [Auth::user()->username]) }}">
                                        <i class="fa fa-user"></i>
                                        Profile
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ action('UserController@getLogout') }}">
                                        <i class="fa fa-reply"></i>
                                        Logout
                                    </a>
                                </li>

                            </ul>

                        </li>
                    @else
                        <li>
                            <a href="{{ action('UserController@getLogin') }}">Login</a>
                        </li>

                        <li>
                            <a href="{{ action('UserController@getRegister') }}">Register</a>
                        </li>
                        <li class="dropdown navbar-profile">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                                Submit
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li>
                                    <a href="{{ action('StaticPagesController@getSubmitModpack') }}">
                                        <i class="fa fa-gear"></i>
                                        Modpack
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ action('ServerController@getAdd') }}">
                                        <i class="fa fa-cloud"></i>
                                        Server
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ action('StaticPagesController@getSubmitVideo') }}">
                                        <i class="fa fa-youtube-play"></i>
                                        Video / Playlist
                                    </a>
                                </li>

                            </ul>

                        </li>
                    @endif
                </ul>

            </nav>

        </div>
        <!-- /.container -->

    </header>


    <div class="mainnav">

        <div class="container">

            <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="fa fa-bars"></i>
            </a>

            <nav class="collapse mainnav-collapse" role="navigation">

                <!--<form class="mainnav-form pull-right" role="search">
                  <input type="text" class="form-control input-md mainnav-search-query" placeholder="Search">
                  <button class="btn btn-sm mainnav-form-btn"><i class="fa fa-search"></i></button>
                </form>-->

                <ul class="mainnav-menu">

                    <li class="dropdown ">

                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
                            Browse
                            <i class="mainnav-caret"></i>
                        </a>

                        <ul class="dropdown-menu" role="menu">

                            <li class="dropdown-submenu">
                                <a href="{{ action('ModpackController@getModpackVersion') }}">
                                    Modpacks
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ action('ModpackController@getModpackVersion', ['1-8-9']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                            1.8.9 Packs
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ action('ModpackController@getModpackVersion', ['1-7-10']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                            1.7.10 Packs
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ action('ModpackController@getModpackVersion', ['1-6-4']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                            1.6.4 Packs
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ action('ModpackController@getModpackVersion') }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                            All Packs
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
                                    Launchers
                                </a>

                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu">
                                        <a href="{{ action('LauncherController@getLauncherVersion', ['feed-the-beast']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/ftb.png') }}"/>
                                            Feed The Beast
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['feed-the-beast', '1-8-9']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                                    1.8.9 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['feed-the-beast', '1-7-10']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                                    1.7.10 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['feed-the-beast', '1-6-4']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                                    1.6.4 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['feed-the-beast']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                                    All Packs
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown-submenu">
                                        <a href="{{ action('LauncherController@getLauncherVersion', ['curse-launcher']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/curse.png') }}"/>
                                            Curse Launcher
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['curse-launcher', '1-8-9']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                                    1.8.9 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['curse-launcher', '1-7-10']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                                    1.7.10 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['curse-launcher', '1-6-4']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                                    1.6.4 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['curse-launcher']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                                    All Packs
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown-submenu">
                                        <a href="{{ action('LauncherController@getLauncherVersion', ['atlauncher']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/atlauncher.png') }}"/>
                                            ATLauncher
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['atlauncher', '1-8-9']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                                    1.8.9 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['atlauncher', '1-7-10']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                                    1.7.10 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['atlauncher', '1-6-4']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                                    1.6.4 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['atlauncher']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                                    All Packs
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown-submenu">
                                        <a href="{{ action('LauncherController@getLauncherVersion', ['technic-platform']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/technic.png') }}"/>
                                            Technic Platform
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['technic-platform', '1-8-9']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                                    1.8.9 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['technic-platform', '1-7-10']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                                    1.7.10 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['technic-platform', '1-6-4']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                                    1.6.4 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['technic-platform']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                                    All Packs
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown-submenu">
                                        <a href="{{ action('LauncherController@getLauncherVersion', ['custom']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                            Custom
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['custom', '1-8-9']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                                    1.8.9 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['custom', '1-7-10']) }}">
                                                    <img class="fa fa-fw icon-menu"
                                                         src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                                    1.7.10 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['custom', '1-6-4']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                                    1.6.4 Packs
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('LauncherController@getLauncherVersion', ['custom']) }}">
                                                    <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                                    All Packs
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown-submenu">
                                <a href="{{ action('ModController@getModVersion') }}">
                                    Mods
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ action('ModController@getModVersion', ['1-8-9']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/blaze.png') }}"/>
                                            1.8.9 Mods
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ action('ModController@getModVersion', ['1-7-10']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/enderman.png') }}"/>
                                            1.7.10 Mods
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ action('ModController@getModVersion', ['1-6-4']) }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/creeper.png') }}"/>
                                            1.6.4 Mods
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ action('ModController@getModVersion') }}">
                                            <img class="fa fa-fw icon-menu" src="{{ asset('/static/img/icons/custom.png') }}"/>
                                            All Mods
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ action('SearchController@getModpackSearch') }}">
                            Pack Finder
                        </a>
                    </li>

                    <li>
                        <a href="{{ action('ModpackController@getCompare') }}">
                            Compare Packs
                        </a>
                    </li>

                    <li>
                        <a href="{{ action('ServerController@getServers') }}">
                            Servers
                        </a>
                    </li>

                    <li>
                        <a href="{{ action('TwitchController@getStreams') }}">
                            Streams
                        </a>
                    </li>

                    {{--<li class="dropdown">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">--}}
                    {{--Modpacks--}}
                    {{--<i class="mainnav-caret"></i>--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu" role="menu">--}}
                    {{--<li>--}}
                    {{--<a href="/modpacks/1-7-10/">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/modpacks/1-6-4/">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/modpacks/">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown">--}}

                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">--}}
                    {{--Launchers--}}
                    {{--<i class="mainnav-caret"></i>--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu" role="menu">--}}

                    {{--<li class="dropdown-submenu">--}}
                    {{--<a href="/launcher/feed-the-beast">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/ftb.png"/>--}}
                    {{--Feed The Beast--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu">--}}
                    {{--<li>--}}
                    {{--<a href="/launcher/feed-the-beast/1-7-10">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/feed-the-beast/1-6-4">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/feed-the-beast">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown-submenu">--}}
                    {{--<a href="/launcher/curse-launcher">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/curse.png"/>--}}
                    {{--Curse Launcher--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu">--}}
                    {{--<li>--}}
                    {{--<a href="/launcher/curse-launcher/1-7-10">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/curse-launcher/1-6-4">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/curse-launcher">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown-submenu">--}}
                    {{--<a href="/launcher/atlauncher">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/atlauncher.png"/>--}}
                    {{--ATLaucher--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu">--}}
                    {{--<li>--}}
                    {{--<a href="/launcher/atlauncher/1-7-10">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/atlauncher/1-6-4">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/atlauncher">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown-submenu">--}}
                    {{--<a href="/launcher/technic-platform">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/technic.png"/>--}}
                    {{--Technic Platform--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu">--}}
                    {{--<li>--}}
                    {{--<a href="/launcher/technic-platform/1-7-10">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/technic-platform/1-6-4">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/technic-platform">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown-submenu">--}}
                    {{--<a href="/launcher/custom">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--Custom--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu">--}}
                    {{--<li>--}}
                    {{--<a href="/launcher/custom/1-7-10">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/custom/1-6-4">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/launcher/custom">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Packs--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown">--}}
                    {{--<a href="/mods" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">--}}
                    {{--Mods--}}
                    {{--<i class="mainnav-caret"></i>--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu" role="menu">--}}
                    {{--<li>--}}
                    {{--<a href="/mods/1-7-10/">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/enderman.png"/>--}}
                    {{--1.7.10 Mods--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/mods/1-6-4/">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/creeper.png"/>--}}
                    {{--1.6.4 Mods--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a href="/mods/">--}}
                    {{--<img class="fa fa-fw icon-menu" src="/static/img/icons/custom.png"/>--}}
                    {{--All Mods--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}

                    @if (isset($user_permissions))
                        <li class="dropdown">
                            <a href="{{ action('ModController@getModVersion') }}" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
                                Site Admin
                                <i class="mainnav-caret"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @if ($user_permissions['author_add'] == 1)
                                    <li>
                                        <a href="{{ action('AuthorController@getAdd') }}">
                                            Add Author
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['mod_add'] == 1)
                                    <li>
                                        <a href="{{ action('ModController@getAdd') }}">
                                            Add Mod
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['mod_import'] == 1)
                                    <li>
                                        <a href="{{ action('ImportController@getStartImport') }}">
                                            Import Mod
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['creator_add'] == 1)
                                    <li>
                                        <a href="{{ action('CreatorController@getAdd') }}">
                                            Add Modpack Creator
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['modpack_add'] == 1)
                                    <li class="dropdown-submenu">
                                        <a href="#">
                                            Add Modpack
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('ModpackController@getAdd', ['1-8-9']) }}">
                                                    1.8.9 Pack
                                                    1.8.9 Pack
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('ModpackController@getAdd', ['1-7-10']) }}">
                                                    1.7.10 Pack
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ action('ModpackController@getAdd', ['1-6-4']) }}">
                                                    1.6.4 Pack
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                                @if ($user_permissions['youtube_add'] == 1)
                                    <li>
                                        <a href="{{ action('YoutubeController@getadd') }}">
                                            Add Youtube Video / Playlist
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['modpack_tag'] == 1)
                                    <li>
                                        <a href="{{ action('ModpackTagController@getAdd') }}">
                                            Add Modpack Tag
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['modpack_code_add'] == 1)
                                    <li>
                                        <a href="{{ action('ModpackCodeController@getAdd') }}">
                                            Add Modpack Code
                                        </a>
                                    </li>
                                @endif
                                @if ($user_permissions['cache_clear'] == 1)
                                    <li class="dropdown-submenu">
                                        <a href="{{ action('AdminController@getClearCache') }}">
                                            Clear Cache
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ action('AdminController@getClearCache', ['mods']) }}">
                                                    Clear Mod Cache
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ action('AdminController@getClearCache', ['modpacks']) }}">
                                                    Clear Modpack Cache
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ action('AdminController@getClearCache', ['launchers']) }}">
                                                    Clear Launcher Cache
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ action('AdminController@getClearCache', ['modpackmods']) }}">
                                                    Clear Modpack's Mods Cache
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ action('AdminController@getClearCache', ['modmodpacks']) }}">
                                                    Clear Mod's Modpacks Cache
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ action('AdminController@getClearCache', ['user-permissions']) }}">
                                                    Clear User Permission Cache
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                </ul>

            </nav>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.mainnav -->
