<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('custom/logo.png') }}" alt="Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AME Application</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">

                @if(Auth::check())

                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ (Request::segment(1) == 'dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @if(Auth::user()->role === 'admin')
                       <li class="nav-item {{ (Request::segment(1) == 'master') ? 'menu-open' : '' }}">
                            <a href="" class="nav-link master-link {{ (Request::segment(1) == 'master') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('locations.index') }}" 
                                    class="nav-link debitor-link {{ (Request::segment(2) == 'locations') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-map"></i>
                                        <p>Location</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('debitor.index') }}" 
                                    class="nav-link debitor-link {{ (Request::segment(2) == 'debitors') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-minus"></i>
                                        <p>Debitor List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('creditor.index') }}" 
                                    class="nav-link creditor-link {{ (Request::segment(2) == 'creditors') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-plus"></i>
                                        <p>Creditor List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('items.index') }}" 
                                    class="nav-link items-link {{ (Request::segment(2) == 'items') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-box"></i>
                                        <p>Items List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('operators.index') }}" 
                                    class="nav-link operator-link {{ (Request::segment(2) == 'operators') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-cog"></i>
                                        <p>Operator</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('drilling.index') }}" 
                            class="nav-link drilling-link {{ (Request::segment(1) == 'drilling') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hard-hat fa-3x"></i>
                                <p>Drilling Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('invoice.index') }}" 
                            class="nav-link invoice-link {{ (Request::segment(1) == 'invoice') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Invoice</p>
                            </a>
                        </li>

                    @endif

                    @if(Auth::user()->role === 'client')

                    @endif

                    <li class="nav-item">
                        <a href="" class="nav-link {{ (Request::segment(1) == 'activity-log') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Activity Log</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="nav-icon fas fa-power-off"></i>
                            <p>Logout</p>
                        </a>
                    </li>

                @endif

            </ul>
        </nav>
    </div>
</aside>
