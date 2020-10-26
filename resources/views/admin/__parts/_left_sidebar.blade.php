<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- Sidebar Start -->
        <div id="sidebar-menu">

            <ul id="side-menu">

                <!-- item -->
                <li class="menu-title">Navigation</li>

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
