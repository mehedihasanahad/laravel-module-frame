@extends('Dashboard::admin.layout.index')

@section('title', 'Role Create')

@section('custom-css')
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
                    <h1>Roles</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
{{--            <div class="card-header">--}}
                {{--                    <div class="btn-group d-flex justify-content-end">--}}
                {{--                        <button class="button btn-secondary">Create Permission</button>--}}
                {{--                    </div>--}}
{{--            </div>--}}

            <!-- card-body -->
            <div class="card-body">
                <form id="userRolesCreateForm" action="{{route('roles.store')}}" method="POST">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="name"> Name</label>
                                <input class="form-control validation" required data-valueMissing="Name is required" data-typeMismatch="Please enter a valid name" id="name" type="text" name="name" placeholder="role-create"
                                       value="{{old('name')}}"/>
                                @error('name')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="name"> Guard Name</label>
                                <input class="form-control validation" required data-valueMissing="Guard Name is required" id="name" type="text" name="guard_name" placeholder="web"
                                       value="{{old('guard_name')}}"/>
                                @error('guard_name')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="name"> Permissions</label>
                                <select class="form-control validation js-example-basic-multiple" name="permissions[]" multiple required data-valueMissing="Permissions is required">
                                    @foreach($permissions as $key => $permission)
                                        <option value="{{$permission->id}}">{{$permission->name}}</option>
                                    @endforeach
                                </select>
                                @error('permissions')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="btn-group d-flex justify-content-end mt-3">
                            <div class="btn-group-sm">
                                <a href="{{route('roles.index')}}" class="btn btn-sm btn-secondary mr-2">Back</a>
                                <button class="btn btn-sm btn-secondary">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--card-body -->
        </div>
        <!-- Default box -->
    </section>
    <!--Main content -->
@endsection

@section('custom-scripts-links')
    <!-- Select 2 -->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
@endsection
@section('custom-scripts')
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                theme: "classic",
                placeholder: "Select Permissions",
                allowClear: true
            });
        });
        CommonFunction.validForm('userRolesCreateForm');
    </script>
@endsection
