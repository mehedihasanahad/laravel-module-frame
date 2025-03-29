<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }} | Forget Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>{{ env('APP_NAME') }}</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <div class="alert alert-dismissible alertDiv" style="display:none">
                <span id="alert_span"></span>
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
            </div>


            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

            <div id="verify_user">
                <div class="input-group mb-3" id="emailDiv">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3" style="display:none;" id="otpDiv">
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="OTP">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="button" id="verify_email" class="btn btn-primary btn-block">Verify</button>
                        <button type="button" id="verify_otp" style="display: none;" class="btn btn-primary btn-block">Verify</button>
                    </div>
                    <!-- /.col -->
                </div>
            </div>

            <div id="pass_change_form" style="display: none;">
                <div class="input-group mb-3" id="emailDiv">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3" id="emailDiv">
                    <input type="password" class="form-control" id="c_password" name="c_password" placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="button" id="change_pass" class="btn btn-primary btn-block">Change Password</button>
                    </div>
                    <!-- /.col -->
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <p class="mb-0">
                    <a href="{{ url('/login') }}" class="text-center">Login</a>
                </p>
                <p class="mb-0">
                    <a href="{{ url('/registration') }}" class="text-center">Register yourself</a>
                </p>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>

<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>

<!-- jquery-validation -->
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
<script>
    $('#verify_email').click(function(){
        let btn = $(this);
        let btn_content = btn.html();
        btn.html(btn_content+ ' <i class="fa fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);

        let emailObj = $('#email');
        let email = emailObj.val();
        if(!email){
            emailObj.addClass('is-invalid').parent().append("<span class='invalid-feedback'>Please enter your registered mobile number</span>");
            btn.html(btn_content);
            btn.prop('disabled', false);
            return false;
        }
        else{
            emailObj.removeClass('is-invalid').next("");
        }
        $('.alertDiv').hide();
        $('#alert_span').html('');
        $('#verify_otp').hide();
        $.ajax({
            url:"{{url('/verify-email')}}",
            method:'POST',
            data:{
                _token: "{{ csrf_token() }}",
                email:email
            },
            success:function(response){
                if(response.responseCode == 1){
                    $('#emailDiv').hide();
                    $('#otpDiv').show();

                    if($('.alertDiv').hasClass('alert-danger')){
                        $('.alertDiv').removeClass('alert-danger');
                    }

                    $('.alertDiv').show().addClass('alert-success');
                    $('#alert_span').html(response.msg);

                    $('#verify_otp').show();
                    $('#verify_email').hide();
                }else{
                    $('#emailDiv').show();
                    $('#otpDiv').hide();

                    if($('.alertDiv').hasClass('alert-success')){
                        $('.alertDiv').removeClass('alert-success');
                    }

                    $('.alertDiv').show().addClass('alert-danger');
                    $('#alert_span').html(response.msg);

                    $('#verify_otp').hide();
                    $('#verify_email').show();
                }

                btn.html(btn_content);
                btn.prop('disabled', false);
            },
            error: function(error) {
                console.log(error);
            }
        });


    });

    $('#verify_otp').click(function(){
        let btn = $(this);
        let btn_content = btn.html();
        btn.html(btn_content+ ' <i class="fa fa-spinner fa-spin"></i>')
        btn.prop('disabled', true);
        let otpObj = $('#otp');
        let otp = otpObj.val();
        let email = $('#email').val();
        if(!otp){
            otpObj.addClass('is-invalid').parent().append("<span class='invalid-feedback'>Please enter your otp</span>");
            btn.html(btn_content);
            btn.prop('disabled', false);
            return false;
        }
        else{
            otpObj.removeClass('is-invalid').next("");
        }
        $('.alertDiv').hide();
        $('#alert_span').html('');
        $.ajax({
            url:"{{url('/verify-otp')}}",
            method:'POST',
            data:{
                _token: "{{ csrf_token() }}",
                email:email,
                otp:otp
            },
            success:function(response){
                btn.html(btn_content);
                btn.prop('disabled', false);
                if(response.responseCode == 1){
                    $('#verify_user').hide();
                    $('#pass_change_form').show();

                    if($('.alertDiv').hasClass('alert-danger')){
                        $('.alertDiv').removeClass('alert-danger');
                    }
                    $('.alertDiv').show().addClass('alert-success');

                    $('#alert_span').html(response.msg);
                }else{
                    $('#verify_user').show();
                    $('#pass_change_form').hide();

                    if($('.alertDiv').hasClass('alert-success')){
                        $('.alertDiv').removeClass('alert-success');
                    }
                    $('.alertDiv').show().addClass('alert-danger');

                    $('#alert_span').html(response.msg);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });


    });

    $('#change_pass').click(function(){
        let btn = $(this);
        let btn_content = btn.html();
        btn.html(btn_content+ ' <i class="fa fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);

        let password = $('#password').val();
        let c_pass = $('#c_password').val();

        let email = $('#email').val();

        if(!password){
            $('#password').addClass('is-invalid').parent().append("<span class='invalid-feedback'>Please enter your new password</span>");
            btn.html(btn_content);
            btn.prop('disabled', false);
            return false;
        }
        else{
            $('#password').removeClass('is-invalid').next("");
        }

        if(!c_pass){
            $('#c_password').addClass('is-invalid').parent().append("<span class='invalid-feedback'>Please confirm your password</span>");
            btn.html(btn_content);
            btn.prop('disabled', false);
            return false;
        }
        else{
            $('#c_password').removeClass('is-invalid').next("");
        }


        if(password != c_pass){
            $('.alertDiv').show();

            if($('.alertDiv').hasClass('alert-success')){
                $('.alertDiv').removeClass('alert-success');
            }
            $('.alertDiv').show().addClass('alert-danger');
            $('#alert_span').html('Password not matched');
            btn.html(btn_content);
            btn.prop('disabled', false);
            return false;
        }

        $('.alertDiv').hide();
        $('#alert_span').html('');

        $.ajax({
            url:"{{url('/change-pass')}}",
            method:'POST',
            data:{
                _token: "{{ csrf_token() }}",
                email:email,
                password:password
            },
            success:function(response){
                btn.html(btn_content);
                btn.prop('disabled', false);
                window.location.href = "{{ url('/login') }}";
            },
            error: function(error) {
                console.log(error);
            }
        });


    });
</script>
</body>
</html>
