<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }} | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <!-- Commont CSS Add -->
    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>{{ env('APP_NAME') }}</b></a>
    </div>
    <!-- /.login-logo -->
    @if(Session::has('message'))
        <div class="alert alert-success">
            {{session('message')}}
        </div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-warning">
            {{ Session::get('error') }}
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card card-outline card-success">
        <div class="card-header text-center">
            <a href="{{'/'}}" class="h1">Login</a>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form id="loginForm" action="{{ url('/login') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input type="email" required data-valueMissing="Email is required" data-typeMismatch="Please enter a valid email address" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div> -->
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <input type="password" required data-valueMissing="Password is required" data-typeMismatch="Please enter a valid password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div> -->
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->
            <div class="d-flex justify-content-between">
                <p class="mb-0">
                    <a href="{{ route('register.form') }}" class="text-center">Register yourself</a>
                </p>
                <p class="mb-0">
                    <a href="{{ url('/forget-password') }}" class="text-center">Forget password?</a>
                </p>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- Common Function -->
<script src="{{ asset('assets/js/validation/validForm.js') }}"></script>
<script src="{{ asset('js/commonFunction.js') }}"></script>
    <script>
        CommonFunction.validForm('loginForm');
    </script>
<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>

</body>
</html>

