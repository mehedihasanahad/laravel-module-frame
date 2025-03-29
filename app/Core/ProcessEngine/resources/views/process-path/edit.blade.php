@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Process Path</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Process Path</li>
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
                <h3 class="card-title">Edit process path form</h3>
            </div>
            <form id="quickForm" method="POST"
                  action="{{ route('process-path.update',\App\Libraries\Encryption::encodeId($process_path->id)) }}">
                @csrf
                @method('PATCH')
                <div class="card-body" x-data="{
                    selectedProcess: {{$process_path->process_type_id}},
                    statusFrom: {{$process_path->status_from}},
                    statusTo: {{$process_path->status_to}},
                    statuses:[],
                    getProcessStatus() {
                        axios.get('/process-status/'+this.selectedProcess)
                            .then(response => {
                                this.statuses = response.data.data;
                            })
                            .catch(error => {
                                console.error(error);
                            })
                    }
                }" x-init="getProcessStatus()">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="process_type_id">Process Type </label>
                                <select class="form-control validation" required id="process_type_id" name="process_type_id"
                                        x-model="selectedProcess" x-on:change="getProcessStatus()">
                                    <option value="" disabled="">Select Process Type</option>
                                    @foreach($process_types as $process)
                                        <option value="{{$process->id}}">{{$process->name}}</option>
                                    @endforeach
                                </select>
                                @error('process_type_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="desk_from">Desk From  </label>
                                <select class="form-control validation" required id="desk_from" name="desk_from">
                                    <option value="" disabled="">Select Desk From</option>
                                    @foreach($user_desks as $desk)
                                        <option
                                            value="{{$desk->id}}" @selected($process_path->desk_from == $desk->id) >{{$desk->name}}</option>
                                    @endforeach
                                </select>
                                @error('desk_from')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="desk_to">Desk To  </label>
                                <select class="form-control validation" required id="desk_to" name="desk_to">
                                    @foreach($user_desks as $desk)
                                        <option
                                            value="{{$desk->id}}" @selected($process_path->desk_to == $desk->id) >{{$desk->name}}</option>
                                    @endforeach
                                </select>
                                @error('desk_to')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status_from">Status From  </label>
                                <select class="form-control validation" required id="status_from" name="status_from" x-model="statusFrom">
                                    <template x-for="status in statuses" :key="status.id">
                                        <option x-bind:value="status.id" x-text="status.status_name"
                                                x-bind:selected="statusFrom == status.id"></option>
                                    </template>
                                </select>
                                @error('status_from')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status_to">Status To  </label>
                                <select class="form-control validation" required id="status_to" name="status_to" x-model="statusTo">
                                    <option value="" disabled="">Select Status To</option>
                                    <template x-for="status in statuses">
                                        <option :value="status.id" x-text="status.status_name"
                                                x-bind:selected="statusTo == status.id"></option>
                                    </template>
                                </select>
                                @error('status_to')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="file_attachment">File Attachment</label>
                                    <div class="form-check-inline validation">
                                        <div class="form-check">
                                            <input class="form-check-input" id="file_attachment_yes" type="radio"
                                                   name="file_attachment"
                                                   value="1" @checked($process_path->file_attachment == 1)>
                                            <label class="form-check-label" for="file_attachment_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" id="file_attachment_no" type="radio"
                                                   name="file_attachment"
                                                   value="0" @checked($process_path->file_attachment == 0)>
                                            <label class="form-check-label" for="file_attachment_no">No</label>
                                        </div>
                                    </div>
                                    @error('file_attachment')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="remarks">Remarks </label>
                                    <div class="form-check-inline validation">
                                        <div class="form-check">
                                            <input class="form-check-input" id="remarks_yes" type="radio" name="remarks"
                                                   value="1" @checked($process_path->remarks == 1)>
                                            <label class="form-check-label" for="remarks_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" id="remarks_no" type="radio" name="remarks"
                                                   value="0" @checked($process_path->remarks == 0)>
                                            <label class="form-check-label" for="remarks_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                @error('remarks')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{route('process-path.index')}}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary">Update</button>
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
