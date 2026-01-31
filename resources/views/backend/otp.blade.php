<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AME Application | OTP</title>

  <!-- Apple Touch Icon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('custom/apple-touch-icon.png') }}">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('custom/favicon.ico') }}">
  <!-- MS Tile Icons -->
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
  <!-- Theme Color -->
  <meta name="theme-color" content="#ffffff">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  @if ($errors->any())
  <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
  @endif
  @if(session()->has('error'))
      <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <ul>
              <li>{{ session()->get('error') }}</li>
          </ul>
      </div>
  @endif
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('success') }}
    </div>
    @endif
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{ route('login') }}" style="font-size: 2.05rem;color: #171819;"><b>AME</b> Application</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg text-center"><b>Two-Factor Authentication</b></p>
        @if(session('title') === 'login' || session('title') === 'forgotpassword' || session('title') === 'signup')
          <p class="login-box-msg">
            @if(session('title') === 'login')
                {{ "A unique code has been sent to your email address. Please check your inbox and enter the code below to complete the login process." }}
            @elseif(session('title') === 'forgotpassword')
              {{ "We've dispatched a code to your email. Find it, and you're only one step away from resetting your password!" }}
            @elseif(session('title') === 'signup')
              {{ "A unique code has been sent to your email address. Please check your inbox and enter the code below to complete the registration process." }}
            @endif
          </p>
          <form action="{{route('otp.submit')}}" method="post">
            @csrf
            <input type="hidden" name="userId" value="{{ request()->route('userId') }}">
            @if(session('title') === 'login' || session('title') === 'signup')
            <input type="hidden" name="title" value="login">
            @elseif(session('title') === 'forgotpassword')
            <input type="hidden" name="title" value="forgotpassword">
            @endif

            <div class="input-group mb-3">
              <input type="text" name="otp" value="{{ old('otp') }}" id="inputFieldOTP" class="form-control" placeholder="Authentication Code">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-key"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <p class="mb-1">
                    <a href="#" id="resend-otp-link">Resend OTP</a>
                </p>
              </div>
              <div class="col-6">
                <button type="submit" class="btn btn-primary btn-block">Submit OTP</button>
              </div>
            </div>
          </form>
        @else 
          <p>"Oops, something isn't quite right here! Let's try that again."</p>
          <a href="{{ route('login') }}" class="btn btn-primary btn-block">Goto Login</a>
        @endif
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<form action="{{ route('otp.resend') }}" method="post" id="resend-otp-form">
    @csrf
    <input type="hidden" name="userId" value="{{ request()->route('userId') }}">
    @if(session('title') === 'login')
    <input type="hidden" name="title" value="login">
    @elseif(session('title') === 'forgotpassword')
    <input type="hidden" name="title" value="forgotpassword">
    @elseif(session('title') === 'signup')
    <input type="hidden" name="title" value="signup">
    @endif

    <button type="submit" style="display: none;">Resend OTP</button>
</form>
<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#resend-otp-link').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            $('#resend-otp-form button[type="submit"]').click();
        });
    });
    document.getElementById('inputFieldOTP').addEventListener('keydown', function(event) {
      if (event.keyCode === 32) {
        event.preventDefault();
      }
    });
    document.getElementById('inputFieldOTP').addEventListener('paste', function(event) {
      event.preventDefault();
      var clipboardData = event.clipboardData || window.clipboardData;
      var pastedData = clipboardData.getData('text');
      var trimmedData = pastedData.replace(/\s/g, ''); // Remove spaces
      document.getElementById('inputFieldOTP').value = trimmedData;
    });
</script>
</body>
</html>
