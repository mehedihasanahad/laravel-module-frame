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
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
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
                <h3 class="card-title">Edit user form</h3>
            </div>
            <!-- /.card-header -->
            <form id="quickForm" method="POST"
                  action="{{ route('users.update',App\Libraries\Encryption::encode($user->id)) }}">
                @method('PATCH')
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" required data-valueMissing="First Name is required" data-typeMismatch="Please enter a valid first name" name="first_name" class="form-control validation" id="first_name"
                                       value="{{ old('first_name', $user->first_name) }}"
                                       placeholder="Enter first name">
                                @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" required data-valueMissing="Last Name is required" data-typeMismatch="Please enter a valid last name" name="last_name" class="form-control validation" id="last_name"
                                       value="{{ old('last_name', $user->last_name) }}"
                                       placeholder="Enter last name">
                                @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nid">National ID</label>
                                <input type="text" required data-valueMissing="National ID is required" data-typeMismatch="Please enter a valid National ID" name="national_id" class="form-control validation" id="nid"
                                       value="{{ old('national_id', $user->national_id) }}"
                                       placeholder="Enter national id">
                                @error('national_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="birth_date">Date of Birth</label>
                                <input type="date" name="birth_date" class="form-control validation" required id="birth_date">
                                <!-- <div class="input-group date" id="birth_date" data-target-input="nearest">
                                    <input type="text" name="birth_date" id="birth_date"
                                           class="form-control datetimepicker-input"
                                           data-target="#birth_date"
                                           value="{{ old('birth_date', $user->birth_date) }}"/>
                                    <div class="input-group-append" data-target="#birth_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div> -->
                                @error('birth_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="block" for="status"> Status</label>
                            <select class="form-control validation" id="status" name="status" required >
                                <option value=""  >Select Status</option>
                                <option value="0" @selected( $user->status == 0)>Inactive</option>
                                <option value="1" @selected( $user->status == 1)>Active</option>
                            </select>
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="block" for="role"> Role</label>
                            <select class="form-control validation" id="role" name="role_id" required >
                                <option value="" >Select Role</option>
                                @foreach($roles as $role)
                                    <option
                                        value="{{$role->id}}" @selected( $user->user_group_id && $role_id == $role->id)>{{$role->name}}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="block" for="desk_id"> Assign Desk</label>
                            <select class="form-control js-example-basic-multiple" id="desk_id" name="desk_id[]" multiple>
                                @foreach($userDesks as $desk)
                                    <option
                                        value="{{$desk->id}}" {{in_array($desk->id, $desks_id)? 'selected' : ''}} >{{$desk->name}}</option>
                                @endforeach
                            </select>
                            @error('desk_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Update</button>
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
    <!-- moment -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Select 2 -->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                theme: "classic",
                placeholder: "Select Desk",
                allowClear: true
            });
        });
        $(function () {
            $('#birth_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });
        CommonFunction.validForm('quickForm');
    </script>
@endsection
