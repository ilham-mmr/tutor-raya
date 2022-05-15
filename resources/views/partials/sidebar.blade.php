<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="{{ asset('assets/images/icon.png') }}" alt="Tutor Raya" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Tutor Raya</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="
                @if (Auth::user()->picture)
                                {{ asset('storage/'.Auth::user()->picture) }}
                            @else
                            {{asset('assets/images/blank-profile.png') }}
                            @endif
                " class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard.home') }}" class="nav-link
                        {{ Request::is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashbboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ Request::is('home/tutor/*') ? 'active' : '' }}">
                        <p>
                            Tutoring
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/home/tutor/profile"
                                class="nav-link {{ Request::is('home/tutor/profile') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/home/tutor/add-tutoring"
                                class="nav-link {{ Request::is('home/tutor/add-tutoring') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Tutoring</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/home/tutor/view-tutoring"
                                class="nav-link {{ Request::is('home/tutor/view-tutoring') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Upcoming Tutoring</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/home/tutor/booked-sessions"
                                class="nav-link {{ Request::is('home/tutor/booked-sessions') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>My Booked Sessions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/home/help" class="nav-link  {{ Request::is('home/setting') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Help
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
