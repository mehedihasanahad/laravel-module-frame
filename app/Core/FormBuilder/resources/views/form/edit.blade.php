@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Form</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Form</li>
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
                <h3 class="card-title">Edit Form</h3>
            </div>
            <form id="quickForm" method="POST" x-data="{
            allErrors:{},
            formData: {{json_encode($data)}},
            tempArr:[],
            updateStepNames() {
                this.tempArr = Array.from({ length: this.formData.steps }, () => ({ name: '' }));
                this.formData.steps_name = this.tempArr;
            }
          }"
                  @submit.prevent="store">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="process_type_id">Process</label>
                                <select class="form-control validation" name="process_type_id" id="process_type_id"
                                        x-model="formData.process_type_id">
                                    <option selected disabled value="{{null}}">Select Process</option>
                                    @foreach($processTypes as $process)
                                        <option value="{{$process->id}}">{{$process->name}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.process_type_id">
                                    <span class="text-danger" x-text="allErrors.process_type_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="form_type"> Form Type</label>
                                <select class="form-control validation" name="form_type" id="form_type"
                                        x-model="formData.form_type">
                                    <option selected disabled value="{{null}}">Select Form Type</option>
                                    @foreach(formTypes() as $formType)
                                        <option value="{{$formType['value']}}">{{$formType['name']}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.form_type">
                                    <span class="text-danger" x-text="allErrors.form_type"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="template_type"> Template Type</label>
                                <select class="form-control validation" name="template_type" id="template_type"
                                        x-model="formData.template_type">
                                    <option selected disabled value="{{null}}">Select Template Type</option>
                                    @foreach(templateTypes() as $templateType)
                                        <option value="{{$templateType['value']}}">{{$templateType['name']}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.template_type">
                                    <span class="text-danger" x-text="allErrors.template_type"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control validation" id="title"
                                       x-model="formData.title"
                                       placeholder="Enter title" value="{{old('title')}}">
                                <template x-if="allErrors.title">
                                    <span class="text-danger" x-text="allErrors.title"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="form_id">Form Id</label>
                                <input type="text" name="form_id" class="form-control validation" id="form_id"
                                       x-model="formData.form_id"
                                       placeholder="Enter form id" value="{{old('form_id')}}">
                                <template x-if="allErrors.form_id">
                                    <span class="text-danger" x-text="allErrors.form_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="app_model_namespace">App Model Namespace</label>
                                <input type="text" name="app_model_namespace" class="form-control validation"
                                       x-model="formData.app_model_namespace"
                                       id="app_model_namespace"
                                       placeholder="Enter App Model Namespace" value="{{old('app_model_namespace')}}">
                                <template x-if="allErrors.app_model_namespace">
                                    <span class="text-danger" x-text="allErrors.app_model_namespace"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="app_id">Related Table Column name</label>
                                <input type="text" name="app_id" class="form-control" id="app_id"
                                       x-model="formData.app_id"
                                       placeholder="Enter Related Table Column name" value="{{old('app_id')}}">
                                <template x-if="allErrors.app_id">
                                    <span class="text-danger" x-text="allErrors.app_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="method">Method</label>
                                <select class="form-control validation" name="method" id="method"
                                        x-model="formData.method">
                                    <option selected disabled value="{{null}}">Select Method</option>
                                    @foreach(methods() as $method)
                                        <option value="{{$method['value']}}">{{$method['name']}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.method">
                                    <span class="text-danger" x-text="allErrors.method"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="action">Action</label>
                                <input type="text" name="action" class="form-control validation" id="action"
                                       x-model="formData.action"
                                       placeholder="Enter action" value="{{old('action')}}">
                                <template x-if="allErrors.action">
                                    <span class="text-danger" x-text="allErrors.action"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="autocomplete">Autocomplete</label>
                                <select class="form-control" name="autocomplete" id="autocomplete"
                                        x-model="formData.autocomplete">
                                    <option selected disabled value="{{null}}">Select Autocomplete</option>
                                    <option value="on">On</option>
                                    <option value="off">Off</option>
                                </select>
                                <template x-if="allErrors.autocomplete">
                                    <span class="text-danger" x-text="allErrors.autocomplete"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="enctype">Form Encryption Type</label>
                                <input type="text" name="enctype" class="form-control " id="enctype"
                                       x-model="formData.enctype"
                                       placeholder="Enter enctype " value="{{old('enctype')}}">
                                <template x-if="allErrors.enctype">
                                    <span class="text-danger" x-text="allErrors.enctype"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control validation" name="status" id="status"
                                        x-model="formData.status">
                                    <option selected disabled value="{{null}}">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <template x-if="allErrors.status">
                                    <span class="text-danger" x-text="allErrors.status"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="steps">Number of Steps</label>
                                <input type="number" name="steps" x-model.number="formData.steps" class="form-control"
                                       x-on:input="updateStepNames()" id="steps"
                                       placeholder="Enter number of steps" value="{{old('steps')}}">
                                <template x-if="allErrors.steps">
                                    <span class="text-danger" x-text="allErrors.steps"></span>
                                </template>
                            </div>
                        </div>
                        <template x-if="formData.steps_name.length > 0">
                        <div class="col-sm-12">
                            <label for="steps_name">Steps Name</label>
                            <template x-for="(name, stepIndex) in formData.steps_name" :key="stepIndex">
                                <div class="row mb-2">
                                    <div class="form-group col-sm-11">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                name="steps_name[]"
                                                x-model="name.name"
                                                class="form-control"
                                                placeholder="Enter steps name"
                                            >
                                            <template
                                                x-if="allErrors.hasOwnProperty(`steps_name.${stepIndex}.name`)">
                                                <span class="text-danger"
                                                      x-text="allErrors[`steps_name.${stepIndex}.name`][0]"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-sm btn-danger" type="button"
                                                @click="formData.steps_name.splice(stepIndex, 1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template x-if="allErrors.steps_name">
                                <span class="text-danger" x-text="allErrors.steps_name"></span>
                            </template>
                        </div>
                        </template>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="form_data_json">Form Data</label>
                                <template x-for="(data, index) in formData.form_data_json" :key="index">
                                    <div class="row mb-2">
                                        <div class="col-sm-3">
                                            <input type="text" x-model="data.key" name="key[]"
                                                   class="form-control"
                                                   placeholder="Enter Form Data Key">
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" x-model="data.value" name="value[]"
                                                   class="form-control"
                                                   placeholder="Enter Form Data Query">
                                        </div>
                                        <div class="col-sm-1">
                                            <template x-if="index === 0 ">
                                                <button class="btn btn-sm btn-secondary" type="button"
                                                        @click="formData.form_data_json.push({ key: '', value: '' })">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </template>
                                            <template x-if="index > 0 ">
                                                <button class="btn btn-sm btn-danger" type="button"
                                                        @click="formData.form_data_json.splice(index, 1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="allErrors.form_data_json">
                                    <span class="text-danger" x-text="allErrors.form_data_json"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end">
                        <div class="btn-group-sm">
                            <a href="{{ route('form.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
    <script>
        function store() {
            axios.patch('{{route('form.update',$data['id'])}}', {
                ...this.formData
            })
                .then(response => {
                    if (response.status === 200) {
                        window.location.href = '{{route('form.index')}}';
                    }
                })
                .catch(error => {
                    if (error.response.status !== 422) {
                        window.location.href = '{{route('form.index')}}';
                    }
                    if (error && error.response.status === 422) {
                        this.allErrors = error.response.data.errors;
                    }
                })
        }
    </script>
@endsection

@section('custom-scripts')
    <script>
        CommonFunction.validForm('quickForm');
    </script>
@endsection


