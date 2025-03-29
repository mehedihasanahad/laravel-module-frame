@extends('Dashboard::admin.layout.index')
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
                    <h1>Edit Process Type</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Process Type</li>
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
                <h3 class="card-title">Edit process type form</h3>
            </div>
            <form id="quickForm" method="POST" action="{{ route('process-type.update', \App\Libraries\Encryption::encodeId($process_type->id)) }}">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Name  </label>
                                <input type="text" required data-valueMissing="Name is required" data-typeMismatch="Please enter a valid name" name="name" class="form-control validation" id="name"
                                       placeholder="Enter name" value="{{old('name',$process_type->name)}}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name_bn">Bn Name  </label>
                                <input type="text" required data-valueMissing="Bn Name is required" data-typeMismatch="Please enter a valid Bn name" name="name_bn" class="form-control validation" id="name_bn" placeholder="Enter bn name"
                                       value="{{ old('name_bn', $process_type->name_bn) }}">
                                @error('name_bn')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="group_name">Group Name  </label>
                                <input type="text" required data-valueMissing="Group Name is required" data-typeMismatch="Please enter a valid group name" name="group_name" class="form-control validation" id="group_name"
                                       placeholder="Enter last name" value="{{old('group_name',$process_type->group_name)}}">
                                @error('group_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="app_table_name">App Table Name 
                                </label>
                                <input type="text" name="app_table_name" required data-valueMissing="App Table Name is required" data-typeMismatch="Please enter a valid app table  name" class="form-control validation" id="app_table_name"
                                       placeholder="Enter master table name" value="{{old('app_table_name',$process_type->app_table_name)}}">
                                @error('app_table_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="module_folder_name">Module Folder Name 
                                </label>
                                <input type="text" name="module_folder_name" class="form-control validation"
                                       required data-valueMissing="Module Folder Name is required" data-typeMismatch="Please enter a valid module folder name"
                                       id="module_folder_name"
                                       placeholder="Enter module folder name" value="{{old('module_folder_name',$process_type->module_folder_name)}}">
                                @error('module_folder_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="panel_color">Panel Color </label>
                                <input type="color" name="panel_color" class="form-control" id="panel_color"
                                       placeholder="Enter panel color" value="{{old('panel_color',$process_type->panel_color)}}">
                                @error('panel_color')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="icon_class">Icon Class </label>
                                <input type="text" name="icon_class" class="form-control" id="icon_class"
                                       placeholder="Enter icon class" value="{{old('icon_class',$process_type->icon_class)}}">
                                @error('icon_class')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="order">Order  </label>
                                <input type="number" name="order" class="form-control validation" id="order"
                                       required data-valueMissing="Order is required" data-typeMismatch="Please enter a valid Order"
                                       placeholder="Enter order" value="{{old('order',$process_type->order)}}">
                                @error('order')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Status  </label>
                                <select class="form-control validation" required data-valueMissing="Status is required" data-typeMismatch="Please enter a valid status" id="status" name="status">
                                    <option value="" selected disabled="">Select Status</option>
                                    <option value="0" @selected( $process_type->status == 0)>Inactive</option>
                                    <option value="1" @selected( $process_type->status == 1)>Active</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="final_status">Final Status  </label>
                                <input type="number" required data-valueMissing="Final Status is required" data-typeMismatch="Please enter a valid final status" name="final_status" class="form-control validation" id="final_status"
                                       placeholder="Enter final Status" value="{{old('final_status',$process_type->final_status)}}">
                                @error('final_status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="permissions">Active Permissions </label>
                            <select class="form-control validation validation js-example-basic-multiple" id="permissions" name="permissions[]" multiple required>
                                @foreach($permissions as $key => $permission)
                                    <option value="{{$permission->name}}"  {{in_array($permission->name, $permittedPermissions)? 'selected' : ''}}>{{$permission->name}}</option>
                                @endforeach
                            </select>
                            @error('permissions')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{ route('process-type.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
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
        CommonFunction.validForm('quickForm');
    </script>
@endsection


