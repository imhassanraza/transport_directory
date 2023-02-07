<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" style="width: 50px;" src="{{ asset('admin_assets/img')}}/{{Auth::guard('admin')->user()->image}}">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                        <span class="block m-t-xs font-bold">Welcome {{ ucwords(Auth::guard('admin')->user()->username) }}</span>
                        <span class="text-muted text-xs block">
                            {{ get_section_content('project', 'site_title') }}
                        </span>
                    </a>
                </div>
                <div class="logo-element">
                    {{ ucwords(Auth::guard('admin')->user()->username) }}
                    <span class="text-muted text-xs block">
                        {{ get_section_content('project', 'short_site_title') }}
                    </span>
                </div>
            </li>

            <li class="{{ Request::is('admin') ? 'active' : '' }}">
                <a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
            </li>

            @if(check_permissions('only_admin'))
            <li class="{{ Request::is('admin/users') ? 'active' : '' }} {{ Request::is('admin/create') ? 'active' : '' }} {{ Request::is('admin/edit*') ? 'active' : '' }}">
                <a href="{{ url('admin/users') }}"><i class="fa-solid fa-users"></i> <span class="nav-label">Users</span></a>
            </li>
            @endif

            @if(check_permissions('cities'))
            <li class="{{ Request::is('admin/city*') ? 'active' : '' }}">
                <a href="{{ url('admin/city') }}"><i class="fa-solid fa-city"></i><span class="nav-label">Cities</span></a>
            </li>
            @endif

            @if(check_permissions('vehicle_types'))
            <li class="{{ Request::is('admin/vehicle-types*') ? 'active' : '' }}">
                <a href="{{ url('admin/vehicle-types') }}"><i class="fa-solid fa-truck"></i> <span class="nav-label">Vehicle Types</span></a>
            </li>
            @endif

            @if(check_permissions('vehicles'))
            <li class="{{ Request::is('admin/vehicles*') ? 'active' : '' }}">
                <a href="{{ url('admin/vehicles') }}"><i class="fa-solid fa-truck-plane"></i> <span class="nav-label">Vehicles</span></a>
            </li>
            @endif

            @if(check_permissions('transporters'))
            <li class="{{ Request::is('admin/transporters*') ? 'active' : '' }}">
                <a href="{{ url('admin/transporters') }}"><i class="fa-solid fa-truck-plane"></i> <span class="nav-label">Transporters</span></a>
            </li>
            @endif

            @if(check_permissions('drivers'))
            <li class="{{ Request::is('admin/drivers*') ? 'active' : '' }}">
                <a href="{{ url('admin/drivers') }}"><i class="fa-solid fa-users-gear"></i> <span class="nav-label">Drviers</span></a>
            </li>
            @endif

            @if(check_permissions('bilties'))
            <li class="{{ Request::is('admin/bilty*') ? 'active' : '' }}">
                <a href="{{ url('admin/bilty') }}"><i class="fa-solid fa-truck-moving"></i> <span class="nav-label">Bilties</span></a>
            </li>
            @endif

            @if(check_permissions('cold_storages'))
            <li class="{{ Request::is('admin/cold-storage*') ? 'active' : '' }}">
                <a href="{{ url('admin/cold-storage') }}"><i class="fa-solid fa-store"></i> <span class="nav-label">Cold Storages</span></a>
            </li>
            @endif

            @if(check_permissions('directory_types'))
            <li class="{{ Request::is('admin/directory_types*') ? 'active' : '' }}">
                <a href="{{ url('admin/directory_types') }}"><i class="fa-solid fa-brands fa-wpforms"></i> <span class="nav-label">Directory Types</span></a>
            </li>
            @endif

            @if(check_permissions('directories'))
            <li class="{{ Request::is('admin/directories*') ? 'active' : '' }}">
                <a href="{{ url('admin/directories') }}"><i class="fa-solid fa-folder-tree"></i> <span class="nav-label">Directories</span></a>
            </li>
            @endif


        </ul>
    </div>
</nav>