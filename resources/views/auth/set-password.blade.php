<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }} | Password Setup</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">

    <style>
        .error-message {
            color: red;
            font-size: 15px;
        }
    </style>
</head>

<body class="hold-transition register-page">

<div class="register-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>{{ env('APP_NAME') }}</b></a>
    </div>
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
    <div class="card card-outline card-warning">
        <div class="card-header text-center">
            <a href="{{'/'}}" class="h1">Password Setup</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Set Your Password</p>
            <form action="{{ route('update-password',request()->segment(2))}}" method="POST" id="RegistrationForm">
                @csrf
                <div class="input-group mb-2">
                    <input type="password" id="password_chk" name="password" class="form-control"
                           placeholder="Password" value="{{old('password')}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                <span class="error-message mb-2">{{ $message }}</span>
                @enderror
                <div class="input-group mb-2">
                    <input type="password" id="conform_password_chk" name="confirmation_password"
                           class="form-control"
                           placeholder="Conform Password" value="{{old('confirmation_password')}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
                </div>
                @error('confirmation_password')
                <span class="error-message mb-2">{{ $message }}</span>
                @enderror
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">Set Password</button>
                </div>
            </form>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/alpine.min.js') }}"></script>
<script src="{{ asset('assets/js/axios.min.js') }}"></script>
</body>

</html>

