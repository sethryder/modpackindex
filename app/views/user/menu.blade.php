<div class="col-md-3 col-sm-5">

    <div class="list-group">

        <a href="/profile/{{{ $user['username'] }}}" class="list-group-item @if ($page == 'profile') active @endif">
            <i class="fa fa-user"></i>Profile

            <i class="fa fa-chevron-right list-group-chevron"></i>
        </a>

        @if ($show_modpacks)
            <a href="/profile/{{{ $user['username'] }}}/modpacks" class="list-group-item @if ($page == 'modpacks') active @endif">
                <i class="fa fa-archive"></i>Modpacks

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif

        @if ($show_mods)
            <a href="/profile/{{{ $user['username'] }}}/mods" class="list-group-item @if ($page == 'mods') active @endif">
                <i class="fa fa-file"></i>Mods

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif

        @if ($show_servers)
            <a href="/profile/{{{ $user['username'] }}}/servers" class="list-group-item @if ($page == 'servers') active @endif">
                <i class="fa fa-cloud"></i>Servers

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif

        @if ($my_profile)
            <a href="/profile/edit" class="list-group-item @if ($page == 'edit') active @endif">
                <i class="fa fa-plus"></i>Edit Profile

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

            <a href="/profile/edit/password" class="list-group-item @if ($page == 'password') active @endif">
                <i class="fa fa-edit"></i>Change Password

                <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>
        @endif
    </div>
    <!-- /.list-group -->

</div>
<!-- /.col -->