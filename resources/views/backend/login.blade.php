<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AME Application | Log in</title>

  <!-- Apple Touch Icon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('custom/apple-touch-icon.png') }}">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('custom/favicon.ico') }}">

  <meta name="theme-color" content="#ffffff">

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">

<div class="login-box">
  <div class="card card-outline card-primary">

    <div class="card-header text-center">
      <a href="{{ route('login') }}" style="font-size:2.05rem;color:#171819;">
        <b>AME</b> Application
      </a>
    </div>

    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      {{-- GLOBAL ERROR (Invalid credentials etc.) --}}
      @error('error')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror

      <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        {{-- EMAIL OR MOBILE --}}
        <div class="input-group mb-3">
          <input type="text"
                 name="email_or_mobile"
                 value="{{ old('email_or_mobile') }}"
                 class="form-control @error('email_or_mobile') is-invalid @enderror"
                 placeholder="Email or Mobile Number"
                 autofocus>

          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>

          @error('email_or_mobile')
            <span class="invalid-feedback d-block">
              {{ $message }}
            </span>
          @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="input-group mb-3">
          <input type="password"
                 name="password"
                 class="form-control @error('password') is-invalid @enderror"
                 placeholder="Password">

          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

          @error('password')
            <span class="invalid-feedback d-block">
              {{ $message }}
            </span>
          @enderror
        </div>

        <div class="row">
          <div class="col-8">
            <a href="{{ route('forgot.password') }}">I forgot my password</a>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
