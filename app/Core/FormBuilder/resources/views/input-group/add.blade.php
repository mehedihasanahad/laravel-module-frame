@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Input Group</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add Input Group</li>
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
                <h3 class="card-title">Add Input Group</h3>
            </div>
            <form id="quickForm" method="POST" action="{{ route('input-group.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="form_id">Form</label>
                                <select class="form-control validation" name="form_id" id="form_id">
                                    <option selected disabled> Select Form</option>
                                    @foreach($forms as $form)
                                        <option
                                            value="{{$form->id}}" @selected(old('form_id') == $form->id)>{{$form->title}}</option>
                                    @endforeach
                                </select>
                                @error('form_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="component_id">Component</label>
                                <select class="form-control validation" name="component_id" id="component_id">
                                    <option selected disabled value="{{null}}">Select  Component</option>
                                </select>
                                @error('component_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="step_no">Step No</label>
                                <input type="text" class="form-control" name="step_no" id="step_no"
                                       placeholder="Enter Step No Title"
                                       value="{{old('step_no')}}">
                                @error('step_no')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="label">Label</label>
                                <input type="text" class="form-control validation" name="label" id="label"
                                       placeholder="Enter Component Title"
                                       value="{{old('label')}}">
                                @error('label')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input type="text" class="form-control" name="value" id="value"
                                       placeholder="Enter Selected Value"
                                       value="{{old('value')}}">
                                @error('value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{ route('input-group.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Create</button>
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
                        $("#component_id").empty();
                        $("#component_id").append($('<option>', {
                            text: 'Select Component'
                        }));
                        $("#component_id").empty();
                        $("#component_id").append($('<option>', {
                            text: 'Select Component'
                        }));
                        parentComponents.forEach(component => {
                            $("#component_id").append($('<option>', {
                                value: component.id,
                                text: component.title
                            }));
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    })
            } else {
                $("#component_id").empty();
                $("#component_id").append($('<option>', {
                    text: 'Select Component'
                }));
            }
        });

        CommonFunction.validForm('quickForm');
    </script>
@endsection


