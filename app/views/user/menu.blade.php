<div class="col-md-3 col-sm-5">

    <div class="list-group">

        <a href="{{ action('UserController@getProfile', [$user['username']]) }}" class="list-group-item @if ($page == 'profile') active @endif">
            <i class="fa fa-user"></i>Profile

            <i class="fa fa-chevron-right list-group-chevron"></i>
        </a>

        @if ($show_modpacks)
            <a href="{{ action('UserController@getModpacks', [$user['username']]) }}" class="list-group-item @if ($page == 'modpacks') active @endif">
                <i class="fa fa-archive"></i>Modpacks

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif

        @if ($show_mods)
            <a href="{{ action('UserController@getMods', [$user['username']]) }}" class="list-group-item @if ($page == 'mods') active @endif">
                <i class="fa fa-file"></i>Mods

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif

        @if ($show_servers)
            <a href="{{ action('UserController@getServers', [$user['username']]) }}" class="list-group-item @if ($page == 'servers') active @endif">
                <i class="fa fa-cloud"></i>Servers

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif

        @if ($my_profile)
            <a href="{{ action('UserController@getEditProfile') }}" class="list-group-item @if ($page == 'edit') active @endif">
                <i class="fa fa-plus"></i>Edit Profile

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

            <a href="{{ action('UserController@getEditPassword') }}" class="list-group-item @if ($page == 'password') active @endif">
                <i class="fa fa-edit"></i>Change Password

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif
    </div>
    <!-- /.list-group -->

</div>
<!-- /.col -->