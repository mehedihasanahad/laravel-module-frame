<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }} | Sign Up</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <!-- Commont CSS Add -->
    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
    <style>
        .error-message {
            color: red;
            font-size: 15px;
        }
    </style>
</head>

<body class="hold-transition register-page">

<div class="register-box">
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
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{'/'}}" class="h1"><b>Sign</b>UP</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Register a new membership</p>
            <form id="registrationForm" action="{{route('login.register')}}" method="post" id="RegistrationForm" x-data="{
            selectedDivision: '',
            selectedDistrict: '',
            selectedUpzila: '',
            loader: {
                district: false,
                upzila: false,
            },
            districts: [],
            upzilas: [],
            getDistricts() {
                this.loader.district = true;
                axios.get('/api/v1/districts/' + this.selectedDivision)
                    .then(response => {
                        this.loader.district = false;
                        if (this.selectedDivision !== null) {
                            this.resetDistrictAndUpzila();
                        }
                        this.districts = response.data.data;
                    })
                    .catch(error => {
                        console.error(error);
                    })
            },
            getUpzilas() {
                this.loader.upzila = true;
                axios.get('/api/v1/upzilas/' + this.selectedDistrict)
                    .then(response => {
                        this.loader.upzila = false;
                        if (this.selectedDistrict !== null) {
                            this.resetUpzila();
                        }
                        this.upzilas = response.data.data;
                    })
                    .catch(error => {
                        console.error(error);
                    })
            },
            resetDistrictAndUpzila() {
                this.selectedDistrict = '';
                this.selectedUpzila = '';
                this.districts = [];
                this.upzilas = [];
            },
            resetUpzila() {
                this.selectedUpzila = '';
                this.upzilas = [];
            }
            }">
                @csrf
                <div class="mb-2">
                    <input type="text" required data-valueMissing="Name is required" data-typeMismatch="Please enter a valid name" id="name_chk" name="name" class="form-control" placeholder="Name"
                           value="{{old('name')}}">
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-user"></i>
                        </div>
                    </div> -->
                    @error('name')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <input type="email" required data-valueMissing="Email is required" data-typeMismatch="Please enter a valid email address" id="email_chk" name="email" class="form-control" placeholder="Email"
                           value="{{old('email')}}">
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div> -->
                    @error('email')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <select class="form-control" name="division_id" x-model="selectedDivision"
                            x-on:change="getDistricts()" required>
                        <option value="">Select Division</option>
                        @foreach($divisions as $division)
                            <option value="{{$division->id}}">{{$division->area_nm}}</option>
                        @endforeach
                    </select>
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-home"></i>
                        </div>
                    </div> -->
                    @error('division_id')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <select class="form-control" name="district_id" x-model="selectedDistrict"
                            x-on:change="getUpzilas()" required>
                        <option value="" disabled selected>Select District</option>
                        <template x-for="district in districts">
                            <option :value="district.id" x-text="district.area_nm"></option>
                        </template>
                    </select>
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-home" x-show="!loader.district"></i>
                            <i class="fas fa-spinner fa-spin" x-show="loader.district"></i>
                        </div>
                    </div> -->
                    @error('district_id')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <select class="form-control" name="upzila_id" x-model="selectedUpzila" required>
                        <option value="" disabled selected>Select Upzila</option>
                        <template x-for="upzila in upzilas">
                            <option :value="upzila.id" x-text="upzila.area_nm"></option>
                        </template>
                    </select>
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-home" x-show="!loader.upzila"></i>
                            <i class="fas fa-spinner fa-spin" x-show="loader.upzila"></i>
                        </div>
                    </div> -->
                    @error('upzila_id')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <input type="tel" pattern="^(?:(?:\+|00)88|01)?\d{11}$" data-valueMissing="Mobile Number is required" data-patternMismatch="Please enter a valid 11-digit mobile number" id="mobile" name="mobile" class="form-control" placeholder="Mobile"
                           value="{{old('mobile')}}" required>
                    @error('mobile')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <input type="number" required data-valueMissing="National ID is required" data-typeMismatch="Please enter a valid national id" id="national_id" name="national_id" class="form-control" placeholder="Nid"
                           value="{{old('national_id')}}">
                    <!-- <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-mobile"></span>
                        </div>
                    </div> -->
                    @error('national_id')
                    <div class="input-group">
                        <span class="error-message">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-check input-group mb-3">
                            <input id="form2Example3" class="form-check-input me-2" type="checkbox"
                                   {{ old('terms_and_condition') ? 'checked' : '' }}
                                   id="form2Example3c" name="terms_and_condition"/>
                            <label class="form-check-label" for="form2Example3">
                                I agree to all statements in <a href="#">Terms of service</a>
                            </label>
                        </div>
                        @error('terms_and_condition')
                        <div class="input-group">
                            <span class="error-message">{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button id="submitBtn" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            {{--                <div class="social-auth-links text-center">--}}
            {{--                    <a href="#" class="btn btn-block btn-primary">--}}
            {{--                        <i class="fab fa-facebook mr-2"></i>--}}
            {{--                        Sign up using Facebook--}}
            {{--                    </a>--}}
            {{--                    <a href="#" class="btn btn-block btn-danger">--}}
            {{--                        <i class="fab fa-google-plus mr-2"></i>--}}
            {{--                        Sign up using Google+--}}
            {{--                    </a>--}}
            {{--                </div>--}}
            <a href="{{url('/login')}}" class="text-center">Already have an Account ?</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- Common Function -->
<script src="{{ asset('assets/js/validation/validForm.js') }}"></script>
<script src="{{ asset('js/commonFunction.js') }}"></script>
<script>
    CommonFunction.validForm('registrationForm');
</script>
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
