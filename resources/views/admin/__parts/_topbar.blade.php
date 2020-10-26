<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <!-- profile -->
            <li class="dropdown notification-list topbar-dropdown">
                <span class="nav-link nav-user mr-0">
                    <img src="{{ asset('images/users/user-9.jpg') }}" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ml-1">
                        {{ Auth::user()->name }}
                    </span>
                </span>
            </li>
            <!-- /profile -->

        </ul>

        <!-- mobile -->
        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <!-- hamburger menu -->
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>
            <!-- /hamburger menu -->
        </ul>
        <!-- /mobile-->

        <div class="clearfix"></div>
    </div>
</div>
