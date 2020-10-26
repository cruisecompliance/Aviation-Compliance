<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <!-- profile -->
            <li class="dropdown notification-list topbar-dropdown">
                <div class="nav-link nav-user mr-0">
                    <img src="{{ asset('images/users/user-9.jpg') }}" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ml-1">
                        {{ Auth::user()->name }}
                    </span>
                </div>
            </li>
            <!-- /profile -->

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="{{ route('user.dashboard') }}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="" height="22">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('images/logo-dark-2.png') }}" alt="" height="20">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                </span>
            </a>

            <a href="{{ route('user.dashboard') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('images/logo-light.png') }}" alt="" height="35">
                </span>
            </a>
        </div>
        <!-- /LOGO -->

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
