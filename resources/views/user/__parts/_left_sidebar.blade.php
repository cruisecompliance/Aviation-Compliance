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
                    <a href="{{ route('user.flows.kanban.index', ['rule_reference' => '', 'rule_section' => '', 'assignee' => Auth::user()->id, 'status' => '', 'finding' => '']) }}">
                        <i data-feather="grid"></i>
                        <span> Flow </span>
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
