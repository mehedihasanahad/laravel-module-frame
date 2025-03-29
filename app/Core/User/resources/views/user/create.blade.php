@extends('Dashboard::admin.layout.index')

@section('custom-css')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select 2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <style>
        .select2-search .select2-search__field {
            width: fit-content !important;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add User</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add user form</h3>
            </div>
            <!-- /.card-header -->
            <form id="quickForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" required data-valueMissing="First Name is required" data-typeMismatch="Please enter a valid first name" name="first_name" class="form-control validation" id="first_name"
                                       placeholder="Enter first name" value="{{old('first_name')}}">
                                @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" required data-valueMissing="Last Name is required" data-typeMismatch="Please enter a valid last name" name="last_name" class="form-control validation" id="last_name"
                                       placeholder="Enter last name" value="{{old('last_name')}}">
                                @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" required data-valueMissing="Email is required" data-typeMismatch="Please enter a valid email address" name="email" class="form-control validation" id="email"
                                       placeholder="Enter email" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" required data-valueMissing="Password is required" data-typeMismatch="Please enter a valid password" name="password" class="form-control validation" id="password"
                                       placeholder="Password" value="{{old('password')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nid">National ID</label>
                                <input type="text" required data-valueMissing="National ID is required" data-typeMismatch="Please enter a valid National ID" name="national_id" class="form-control validation" id="nid"
                                       placeholder="Enter national id" value="{{old('national')}}">
                                @error('national_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="birth_date" class="form-control validation" required data-valueMissing="Date of Birth is required" id="birth_date">
                                <!-- <div class="input-group date" id="birth_date" data-target-input="nearest">
                                    <input type="text" name="birth_date" required data-valueMissing="Date of Birth is required" class="form-control datetimepicker-input"
                                           data-target="#birth_date" value="{{old('birth_date')}}"/>
                                    <div class="input-group-append"  data-target="#birth_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div> -->
                                @error('birth_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="block" for="roles"> Role</label>
                            <select class="form-control validation" id="roles" name="roles" required>
                                <option value="" >Select Role</option>
                                @foreach($roles as $key => $role)
                                    <option value="{{$key}}" @selected(old("roles") == $key )>{{$role}}</option>
                                @endforeach
                            </select>

                            @error('roles')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="block" for="desk_id"> Assign Desk</label>
                            <select class="form-control js-example-basic-multiple" id="desk_id" name="desk_id[]"
                                    multiple>
                                @foreach($userDesks as $desk)
                                    <option value="{{$desk->id}}">{{$desk->name}}</option>
                                @endforeach
                            </select>
                            @error('desk_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="block" for="roles"> </label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="terms" class="custom-control-input"
                                   id="exampleCheck1" {{ old('terms') ? 'checked' : '' }}>

                            <label class="custom-control-label" for="exampleCheck1">I agree to the <a href="#">terms
                                    of service</a>.</label>
                        </div>
                        @error('terms')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Create</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /.card-body -->
        </div>

        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('custom-scripts-links')
    <!-- Select 2 -->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
    <!-- moment -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $('.js-example-basic-multiple').select2({
                theme: "classic",
                placeholder: "Select Desk",
                allowClear: true
            });
            $('#birth_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });

        CommonFunction.validForm('quickForm');
    </script>
@endsection
