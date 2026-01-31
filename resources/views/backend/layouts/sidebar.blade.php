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
                    @endif

                    @if(Auth::user()->role === 'client')

                        <li class="nav-item {{ (Request::segment(1) == 'manage-room') ? 'menu-open' : '' }}">
                            <a href="" class="nav-link {{ (Request::segment(1) == 'manage-room') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bed"></i>
                                <p>
                                    Room Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'create-room') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create Room</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'room-list') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Room List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ (Request::segment(1) == 'manage-guest') ? 'menu-open' : '' }}">
                            <a href="" class="nav-link {{ (Request::segment(1) == 'manage-guest') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-secret"></i>
                                <p>
                                    Guest Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'add-guest') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Guest</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'guest-list') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Guest List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'view-guest') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Guest</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'discontinue-guest') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Discontinue Guest</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ (Request::segment(1) == 'manage-billing') ? 'menu-open' : '' }}">
                            <a href="" class="nav-link {{ (Request::segment(1) == 'manage-billing') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money"></i>
                                <p>
                                    Billing Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'unpaid-list') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Unpaid List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'paid-list') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Paid List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ (Request::segment(1) == 'setting') ? 'menu-open' : '' }}">
                            <a href="" class="nav-link {{ (Request::segment(1) == 'setting') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Setting
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'design-mail-template') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Design Mail Template</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link {{ (Request::segment(2) == 'receipt-bill') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Receipt Bill</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="" class="nav-link {{ (Request::segment(1) == 'subscription-billing') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>Subscription Billing</p>
                            </a>
                        </li>

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
