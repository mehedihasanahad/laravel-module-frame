@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Component</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Component</li>
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
                <h3 class="card-title">Edit Component</h3>
            </div>
            <form id="quickForm" method="POST"
                  action="{{ route('component.update',\App\Libraries\Encryption::encodeId($component->id)) }}">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="form_id">Form</label>
                                <select class="form-control validation" name="form_id" id="form_id">
                                    <option selected disabled> Select Form</option>
                                    @foreach($forms as $form)
                                        <option
                                            value="{{$form->id}}" @selected( old('form_id',$component->form_id) == $form->id)>{{$form->title}}</option>
                                    @endforeach
                                </select>
                                @error('form_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="parent_id">Parent Component</label>
                                <select class="form-control" name="parent_id" id="parent_id">
                                    <option selected disabled>Select Parent Component</option>
                                    @foreach($parentComponents as $parentComponent)
                                        <option
                                            value="{{$parentComponent->id}}" @selected( old('parent_id',$parentComponent->parent_id) == $component->parent_id)>{{$component->title}}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="title">Component Title</label>
                                <input type="text" class="form-control validation" name="title" id="title"
                                       placeholder="Enter Component Title"
                                       value="{{old('title',$component->title)}}">
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="is_loop">Is Looped ?</label>
                                <select class="form-control validation" name="is_loop" id="is_loop">
                                    <option selected disabled>Select Looped</option>
                                    <option value="1" @selected( old('is_loop',$component->is_loop) == 1)>Yes</option>
                                    <option value="0" @selected( old('is_loop',$component->is_loop) == 0)>No</option>
                                </select>
                                @error('is_loop')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="loop_data">Loop Data Key</label>
                                <input type="text" class="form-control " name="loop_data" id="loop_data"
                                       placeholder="Enter Loop Data Key"
                                       value="{{old('loop_data',$component->loop_data)}}">
                                @error('loop_data')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="template_type">Template Type</label>
                                <select class="form-control validation" name="template_type" id="template_type">
                                    <option selected disabled>Select Template Type</option>
                                    <option value="1" @selected(old('template_type',$component->template_type) == 1)>2
                                        Column Grid
                                    </option>
                                    <option value="2" @selected(old('template_type',$component->template_type) == 2)>4
                                        Column Grid
                                    </option>
                                    <option value="3" @selected(old('template_type',$component->template_type) == 3)>
                                        Tabular Form
                                    </option>
                                </select>
                                @error('template_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="order">Order</label>
                                <input type="number" class="form-control validation" name="order" id="order"
                                       placeholder="Enter Component Order"
                                       value="{{old('order',$component->order)}}">
                                @error('order')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="step_no">Step No</label>
                                <input type="number" class="form-control " name="step_no" id="step_no"
                                       placeholder="Enter Step No"
                                       value="{{old('step_no',$component->step_no)}}">
                                @error('step_no')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control validation" name="status" id="status">
                                    <option selected disabled>Select Status</option>
                                    <option value="1" @selected(old('status',$component->status) == 1)>Active</option>
                                    <option value="0" @selected(old('status',$component->status) == 0)>Inactive</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{ route('component.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
    <script>

    </script>
@endsection

@section('custom-scripts')
    <script>
        $("#form_id").on("change", function () {
            let selectedForm = $(this).val();
            if (selectedForm) {
                axios.get('/form-builder/component/' + selectedForm)
                    .then(response => {
                        let parentComponents = response.data.data;
                        $("#parent_id").empty();
                        $("#parent_id").append($('<option>', {
                            text: 'Select Parent Component'
                        }));
                        $("#parent_id").empty();
                        $("#parent_id").append($('<option>', {
                            text: 'Select Parent Component'
                        }));
                        parentComponents.forEach(component => {
                            $("#parent_id").append($('<option>', {
                                value: component.id,
                                text: component.title
                            }));
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    })
            } else {
                $("#parent_id").empty();
                $("#parent_id").append($('<option>', {
                    text: 'Select Parent Component'
                }));
            }
        });
        CommonFunction.validForm('quickForm');
    </script>
@endsection

