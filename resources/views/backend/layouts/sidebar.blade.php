<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('custom/logo.jpg') }}" alt="Logo"
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
                            <a href="" class="nav-link {{ (Request::segment(1) == 'master') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('debitor.index') }}" class="nav-link {{ (Request::segment(2) == 'debitors') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Debitor List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'twilio-account') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Creditor List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('items.index') }}" class="nav-link {{ (Request::segment(2) == 'items') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Items List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ (Request::segment(1) == 'operators') ? 'menu-open' : '' }}">
                            <a href="" class="nav-link {{ (Request::segment(1) == 'operators') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>
                                    Operator
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('operators.index') }}" class="nav-link {{ (Request::segment(2) == '') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Operator List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('operators.payment') }}" class="nav-link {{ (Request::segment(2) == 'payment') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Operator Payment</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('drilling.index') }}" class="nav-link {{ (Request::segment(1) == 'drilling') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hard-hat"></i>
                                <p>Drilling Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('invoice.index') }}" class="nav-link {{ (Request::segment(1) == 'invoice') ? 'active' : '' }}">
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
