<div class="left-side-menu">

    <div class="hamburger-menu">
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
    </div>

    <div class="h-100" data-simplebar>

        <!-- Sidebar Start -->
        <div id="sidebar-menu">

            <ul id="side-menu">

                <!-- item -->
                <li class="menu-title">Compliance</li>

                <li>
                    <a href="{{ route('admin.requirements.index') }}">
                        <i data-feather="layers"></i>
                        <span> Requirements </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.flows.index') }}">
                        <i data-feather="grid"></i>
                        <span> Flow </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.companies.index') }}">
                        <i data-feather="activity"></i>
                        <span> Companies </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <i data-feather="users"></i>
                        <span> Users </span>
                    </a>
                </li>

                <div class="dropdown-divider"></div>
                <li>
                    <a href="#">
                        <div class="nav-link nav-user mr-0">
                            {{--                        <img src="{{ asset('images/users/user-9.jpg') }}" alt="user-image" class="rounded-circle">--}}
                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=20" alt="user" class="rounded-circle">
                            <span class="pro-user-name ml-1">
                            {{ Auth::user()->name }}
                        </span>
                        </div>
                    </a>

                </li>
                <li>
                    <a href="{{ route('logout') }}" onclick="
                        event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <!-- /item -->

            </ul>

        </div>
        <!-- Sidebar End -->

        <div class="clearfix"></div>

    </div>

</div>
