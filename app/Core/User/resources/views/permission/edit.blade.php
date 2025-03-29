@extends('Dashboard::admin.layout.index')

@section('title', 'Permissions Create')

@section('custom-css')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Permissions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Permissions</li>
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
                <form id="editPermissionForm" action="{{route('permissions.update', \App\Libraries\Encryption::encode($permission->id))}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="name"> Name</label>
                                <input class="form-control validation" required data-valueMissing="Name is required" data-typeMismatch="Please enter a valid name" id="name" type="text" name="name" placeholder="role-create" value="{{$permission->name}}"/>
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="name"> Guard Name</label>
                                <input class="form-control validation" required data-valueMissing="Guard Name is required" data-typeMismatch="Please enter a guard name" id="name" type="text" name="guard_name" placeholder="web" value="{{$permission->guard_name}}"/>
                                @error('guard_name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group d-flex justify-content-end mt-3">
                                <div class="btn-group-sm">
                                    <a href="{{route('permissions.index')}}" class="btn btn-sm btn-secondary mr-2">Back</a>
                                    <button class="btn btn-sm btn-secondary">Update</button>
                                </div>
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

@section('custom-scripts')
    <script>
        CommonFunction.validForm('editPermissionForm');
    </script>
@endsection

