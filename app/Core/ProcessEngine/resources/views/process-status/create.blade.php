@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Process Status</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add Process Status</li>
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
                <h3 class="card-title">Add process status form</h3>
            </div>
            <form id="quickForm" method="POST" action="{{ route('process-statuses.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row"  x-data="{
    selectedStatus: ''
}">
                        <div class="col-sm-6 d-none">
                            <div class="form-group">
                                <label for="status_name">Status Name</label>
                                <input type="text" name="status_name" readonly class="form-control validation" id="status_name"
                                       placeholder="Enter status_name" value="{{old('status_name')}}" x-model="selectedStatus">
                                @error('status_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="id">Status</label>
                                <select class="form-control validation" required id="id" name="id"  x-on:change="selectedStatus = $event.target.selectedOptions[0].dataset.name">
                                    <option value="" selected disabled="">Select Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}"
                                                data-name="{{$status->name}}" {{ old('id') !==null && old('id')== $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                                @error('id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="process_type_id">Process Type</label>
                                <select class="form-control validation" required id="process_type_id" name="process_type_id">
                                    <option value="" selected disabled="">Select Process Type</option>
                                    @foreach($processTypes as $process)
                                        <option
                                            value="{{$process->id}}" {{old('process_type_id')== $process->id ?'selected':'' }} >{{$process->name}}</option>
                                    @endforeach
                                </select>
                                @error('process_type_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="color" name="color" class="form-control" id="color"
                                       placeholder="Enter name" value="{{old('color')}}">
                                @error('color')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control validation" required id="status" name="status">
                                    <option value="" selected disabled="">Select Status</option>
                                    <option value="0" {{old('status') !==null && old('status') == 0 ? 'selected' :''}}>
                                        Inactive
                                    </option>
                                    <option value="1" {{old('status') == 1 ? 'selected' :''}}>Active</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="addon_status">Addon Status</label>
                                <select class="form-control validation" required id="status" name="addon_status">
                                    <option value="" selected disabled="">Select Addon Status</option>
                                    <option
                                        value="0" {{old('addon_status') !==null && old('addon_status') == 0 ? 'selected' :''}}>
                                        Inactive
                                    </option>
                                    <option value="1" {{old('addon_status') == 1 ? 'selected' :''}}>Active</option>
                                </select>
                                @error('addon_status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end">
                        <div class="btn-group-sm">
                            <a href="{{ route('process-statuses.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('custom-scripts')
    <script>
        CommonFunction.validForm('quickForm');
    </script>
@endsection


