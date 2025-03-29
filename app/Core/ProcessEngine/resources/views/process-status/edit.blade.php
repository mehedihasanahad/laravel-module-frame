@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Process Status</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Process Status</li>
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
                <h3 class="card-title">Edit process status form</h3>
            </div>
            <form id="quickForm" method="POST"
                  action="{{ route('process-statuses.update',[
                    'process_status'=>\App\Libraries\Encryption::encodeId($process_status->id),
                    'process_type_id'=>\App\Libraries\Encryption::encodeId($process_status->process_type_id)]) }}">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row" x-data="{
    selectedStatus: ''
}" x-init="  selectedStatus = '{{ old('status_name') ?? $process_status->status_name  }}';">
                        <div class="col-sm-6 d-none">
                            <div class="form-group">
                                <label for="status_name">Status Name</label>
                                <input type="text" name="status_name" readonly class="form-control validation" required data-valueMissing="Status Name is required" data-typeMismatch="Please enter a valid status name" id="status_name"
                                       placeholder="Enter status_name" value="{{old('status_name',$process_status->status_name)}}"
                                       x-model="selectedStatus">
                                @error('status_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="id">Status</label>
                                <select class="form-control validation" required id="id" name="id"
                                        x-on:change="selectedStatus = $event.target.selectedOptions[0].dataset.name">
                                    <option value="" disabled="">Select Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}"
                                                data-name="{{$status->name}}" {{$process_status->id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
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
                                            value="{{$process->id}}" @selected($process->id == $process_status->process_type_id )>{{$process->name}}</option>
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
                                       placeholder="Enter name" value="{{old('color',$process_status->color)}}">
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
                                    <option value="0" @selected($process_status->status == 0)>Inactive</option>
                                    <option value="1" @selected($process_status->status == 1)>Active</option>
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
                                    <option value="0" @selected($process_status->addon_status == 0)>Inactive</option>
                                    <option value="1" @selected($process_status->addon_status == 1)>Active</option>
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
                            <button class="btn btn-sm btn-secondary ">Update</button>
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


