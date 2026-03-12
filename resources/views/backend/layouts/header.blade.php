<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel') | AME</title>

  <!-- Apple Touch Icon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('custom/apple-touch-icon.png') }}">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('custom/favicon.ico') }}">
  <!-- MS Tile Icons -->
  <meta name="msapplication-TileColor" content="#ffffff">
  <!-- Theme Color -->
  <meta name="theme-color" content="#ffffff">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Tempusdominus -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">

  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

  <!-- pace-progress -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('custom/css/breadcrum.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/css/choosen.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/css/loader.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/css/search.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/css/menu-icons.css') }}">

  <!-- CK Editor -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/bs-stepper/css/bs-stepper.min.css') }}">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    @stack('styles')

</head>

<body class="
  @if(!empty(Auth::user()) && Auth::user()->dark_mode == 1) dark-mode @endif
  hold-transition sidebar-mini sidebar-collapse layout-navbar-fixed layout-fixed">

@php
  $user = auth()->user();
@endphp

<div id="roleTag" data-role-tag="{{ $user->role }}"></div>
<div id="darkModeStatus" data-dark-mode="{{ $user->dark_mode }}"></div>
<div id="paymentStatus" data-client-list-route="{{ $user->paid_status }}"></div>

<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand
    @if(!empty(Auth::user()) && Auth::user()->dark_mode == 1) navbar-dark @else navbar-purple @endif">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block text-bold">
        <span class="nav-link text-bold">
          @if(!empty(Auth::user()))
            Welcome {{ Auth::user()->name }}
          @endif
        </span>
      </li>
      <li class="nav-item dropdown mr-3">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <span class="badge badge-info px-3 py-2" title="Change Financial Year">
            <i class="fas fa-calendar-alt mr-1"></i>
            FY {{ session('financial_year_name', 'N/A') }}
          </span>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
          @forelse($financialYears as $fy)
            <a href="{{ route('financial-year.switch', $fy->id) }}"
               class="dropdown-item d-flex justify-content-between align-items-center
               {{ session('financial_year_id') == $fy->id ? 'active font-weight-bold' : '' }}">

              {{ $fy->name }}

              @if(session('financial_year_id') == $fy->id)
                <i class="fas fa-check text-success text-white"></i>
              @endif
            </a>
          @empty
            <span class="dropdown-item text-muted">
              No Financial Years
            </span>
          @endforelse
        </div>
      </li>





    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form id="searchForm" class="form-inline">
            <div class="input-group input-group-sm">
              <input id="searchInput" class="form-control form-control-navbar" type="search"
                     placeholder="Search" aria-label="Search" autocomplete="off">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="sidebar-search-results">
              <div class="list-group scrollable-div"></div>
            </div>
          </form>
        </div>
      </li>

      <li class="nav-item dropdown" data-toggle="tooltip" data-placement="bottom" title="Dark Mode">
        <a href="" class="nav-link darkMode">
          <input type="hidden" class="custom-control-input" id="customSwitch1"
                 @if(!empty(Auth::user()) && Auth::user()->dark_mode == 1) checked @endif>
          <i class="fas fa-moon"></i>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge notification_count"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right head_notification scrollable-div"></div>
      </li>

      <li class="nav-item dropdown" title="Your Profile">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-user"></i>
        </a>
      </li>

      <li class="nav-item dropdown" title="Logout (बहार निकले)">

        <a href="#"
          class="nav-link"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-power-off"></i>
        </a>

        <form id="logout-form"
              action="{{ route('logout') }}"
              method="POST"
              class="d-none">
            @csrf
        </form>

      </li>

    </ul>
  </nav>
  <!-- /.navbar -->
