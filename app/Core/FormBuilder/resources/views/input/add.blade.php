@extends('Dashboard::admin.layout.index')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Input</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add Input</li>
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
                <h3 class="card-title">Add Input</h3>
            </div>
            <form id="quickForm" method="POST" x-data="data" @submit.prevent="storeInput">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="form_id">Form</label>
                                <select class="form-control validation" name="form_id" id="form_id"
                                        x-model="formData.form_id">
                                    <option selected disabled value="{{null}}"> Select Form</option>
                                    @foreach($forms as $form)
                                        <option
                                            value="{{$form->id}}" @selected(old('form_id') == $form->id)>{{$form->title}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.form_id">
                                    <span class="text-danger" x-text="allErrors.form_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="component_id">Component</label>
                                <select class="form-control validation" name="component_id" id="component_id"
                                        x-model="formData.component_id">
                                    <option selected disabled value="{{null}}">Select Component</option>
                                </select>
                                <template x-if="allErrors.component_id">
                                    <span class="text-danger" x-text="allErrors.component_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="label">Label</label>
                                <input type="text" class="form-control validation" id="label" name="label"
                                       x-model="formData.label"
                                       placeholder="Enter Form Label">
                                <template x-if="allErrors.label">
                                    <span class="text-danger" x-text="allErrors.label"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="label_position">Label Position</label>
                                <select class="form-control validation" name="label_position" id="label_position"
                                        x-model="formData.label_position">
                                    <option selected disabled value="{{null}}">Select Label Position</option>
                                    @foreach(labelPositions() as $labelPosition)
                                        <option
                                            value="{{$labelPosition['value']}}" @selected(old('label_position') == $labelPosition['value'])>{{$labelPosition['name']}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.label_position">
                                    <span class="text-danger" x-text="allErrors.label_position"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="width">Width</label>
                                <select class="form-control validation" name="width" id="width"
                                        x-model="formData.width">
                                    <option selected disabled value="{{null}}">Select Input Width</option>
                                    @foreach(inputWidths() as $width)
                                        <option
                                            value="{{$width['value']}}" @selected(old('width') == $width['value'])>{{$width['name']}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.width">
                                    <span class="text-danger" x-text="allErrors.width"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="group_id">Parent Group Input</label>
                                <select class="form-control" name="group_id" id="group_id"
                                        x-model="formData.group_id">
                                    <option selected disabled value="{{null}}">Select Input</option>
                                </select>
                                <template x-if="allErrors.group_id">
                                    <span class="text-danger" x-text="allErrors.group_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="input_tag_name">Input Type</label>
                                <select class="form-control validation" name="input_tag_name" id="input_tag_name"
                                        x-model="formData.input_tag_name">
                                    <option selected disabled value="{{null}}">Select Input Type</option>
                                    @foreach(inputTags() as $inputType)
                                        <option
                                            value="{{$inputType}}" @selected(old('input_tag_name') == $inputType)>{{ucfirst($inputType)}}</option>
                                    @endforeach
                                </select>
                                <template x-if="allErrors.input_tag_name">
                                    <span class="text-danger" x-text="allErrors.input_tag_name"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="is_loop">Is Looped?</label>
                                <select class="form-control validation" name="is_loop" id="is_loop"
                                        x-model="formData.is_loop">
                                    <option selected disabled value="{{null}}">Select Looped Status</option>
                                    <option value="1" @selected(old('is_loop') == 1)>Yes</option>
                                    <option value="0" @selected(old('is_loop') == 0)>No</option>
                                </select>
                                <template x-if="allErrors.is_loop">
                                    <span class="text-danger" x-text="allErrors.is_loop"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="loop_data">Loop Data</label>
                                <input type="text" class="form-control" id="loop_data" name="loop_data"
                                       x-model="formData.loop_data"
                                       placeholder="Enter Loop Data key">
                                <template x-if="allErrors.loop_data">
                                    <span class="text-danger" x-text="allErrors.loop_data"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="input_id">Input Id</label>
                                <input type="text" class="form-control validation" id="input_id" name="input_id"
                                       x-model="formData.input_id"
                                       placeholder="Enter Input Id">
                                <template x-if="allErrors.input_id">
                                    <span class="text-danger" x-text="allErrors.input_id"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control validation" id="name" name="name"
                                       x-model="formData.name"
                                       placeholder="Enter name">
                                <template x-if="allErrors.name">
                                    <span class="text-danger" x-text="allErrors.name"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="model_namespace">Model Namespace</label>
                                <input type="text" class="form-control validation" id="model_namespace"
                                       name="model_namespace"
                                       x-model="formData.model_namespace"
                                       placeholder="Enter Model Namespace">
                                <template x-if="allErrors.model_namespace">
                                    <span class="text-danger" x-text="allErrors.model_namespace"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="column_name">Column name</label>
                                <input type="text" class="form-control validation" id="column_name" name="column_name"
                                       x-model="formData.column_name"
                                       placeholder="Enter Column name">
                                <template x-if="allErrors.column_name">
                                    <span class="text-danger" x-text="allErrors.column_name"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="order">Order</label>
                                <input type="number" class="form-control validation" id="order" name="order"
                                       x-model.number="formData.order"
                                       placeholder="Enter Column name">
                                <template x-if="allErrors.order">
                                    <span class="text-danger" x-text="allErrors.order"></span>
                                </template>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control validation" name="status" id="status"
                                        x-model="formData.status">
                                    <option selected disabled value="{{null}}">Select Status</option>
                                    <option value="1" @selected(old('status') == 1)>Active</option>
                                    <option value="0" @selected(old('status') == 0)>Inactive</option>
                                </select>
                                <template x-if="allErrors.status">
                                    <span class="text-danger" x-text="allErrors.status"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-sm-12 border border-success">
                            <div class="card-header">
                                <label class="card-title">Attribute Bag</label>
                            </div>
                            <div class="card-body">
                                <template x-for="(attribute, index) in formData.attribute_bag" :key="index">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="key" name="key[]"
                                                       x-model="attribute.key"
                                                       placeholder="Enter Attribute Key">
                                                <template
                                                    x-if=" allErrors.hasOwnProperty(`attribute_bag.${index}.key`)">
                                                    <span class="text-danger"
                                                          x-text="allErrors[`attribute_bag.${index}.key`][0]"></span>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="value"
                                                       name="value[]"
                                                       x-model="attribute.value"
                                                       placeholder="Enter Attribute Value">
                                                <template
                                                    x-if="allErrors.hasOwnProperty(`attribute_bag.${index}.value`)">
                                                    <span class="text-danger"
                                                          x-text="allErrors[`attribute_bag.${index}.value`][0]"></span>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <template x-if="index === 0">
                                                <button type="button" class="btn btn-sm btn-success"
                                                        @click="formData.attribute_bag.push({key: '', value: ''})">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </template>
                                            <template x-if="index > 0">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        @click="formData.attribute_bag.splice(index,1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <template x-if="allErrors.attribute_bag">
                                <div class="card-footer">
                                    <span class="text-danger" x-text="allErrors.attribute_bag"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-sm-12 border border-success">
                            <div class="card-header">
                                <h4 class="card-title">Validations</h4>
                            </div>
                            <div class="card-body">
                                <template x-for="(validation, validationIndex) in formData.validation"
                                          :key="validationIndex">
                                    <div class="row mb-3">
                                        <div class="card col-sm-12 border border-success">
                                            <div class="card-header">
                                                <h2 class="card-title" x-text="'Validation '+(validationIndex+1)"></h2>
                                                <div class="card-tools">
                                                    <template x-if="validationIndex === 0">
                                                        <button type="button" class="btn btn-sm btn-success"
                                                                @click="addValidationRule">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </template>
                                                    <template x-if="validationIndex > 0">
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                                @click="removeValidationRule(validationIndex,1)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="col-form-label">Rules</div>
                                                    <template x-for="(rule, ruleIndex) in validation.rules"
                                                              :key="ruleIndex">
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control" x-model="rule.rule"
                                                                   placeholder="Enter Validation Rule">
                                                            <template
                                                                x-if="allErrors.hasOwnProperty(`validation.rules.${ruleIndex}.rule`)">
                                                                <div class="invalid-feedback"
                                                                     x-text="allErrors[`validation.rules.${ruleIndex}.rule`][0]"></div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div class="mb-32">
                                                    <div class="col-form-label">Messages</div>
                                                    <template x-for="(message, messageIndex) in validation.message"
                                                              :key="messageIndex">
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"
                                                                           x-model="message.key"
                                                                           placeholder="Enter Message Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"
                                                                           x-model="message.value"
                                                                           placeholder="Enter Message Value">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <template x-if="allErrors.validation">
                                <div class="card-footer">
                                    <span class="text-danger" x-text="allErrors.validation"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group d-flex justify-content-end mt-3">
                        <div class="btn-group-sm">
                            <a href="{{ route('component.index') }}" class="btn btn-sm btn-secondary mr-2">Back</a>
                            <button class="btn btn-sm btn-secondary ">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
    <script>
        function data() {
            return {
                allErrors: {},
                formData: {
                    form_id: '',
                    component_id: '',
                    label: '',
                    label_position: '',
                    width: '',
                    group_id: '',
                    input_tag_name: '',
                    is_loop: '',
                    loop_data: '',
                    input_id: '',
                    name: '',
                    attribute_bag: [
                        {
                            key: '',
                            value: '',
                        }
                    ],
                    validation: [
                        {
                            rules: [
                                {
                                    rule: '',
                                }
                            ],
                            message: [
                                {
                                    key: '',
                                    value: '',
                                }
                            ],
                        }
                    ],
                    model_namespace: '',
                    column_name: '',
                    order: '',
                    status: '',
                },
                storeInput() {
                    axios.post('{{route('input.store')}}', this.formData)
                        .then(response => {
                            if (response.status === 200) {
                                window.location.href = '{{route('input.index')}}';
                            }
                        })
                        .catch(error => {
                            if (error.response.status !== 422) {
                                window.location.href = '{{route('input.index')}}';
                            }
                            if (error && error.response.status === 422) {
                                this.allErrors = error.response.data.errors;
                            }
                        })
                },
                addValidationRule() {
                    this.formData.validation.push({
                        rules: [
                            {
                                rule: '',
                            }
                        ],
                        message: [
                            {
                                key: '',
                                value: '',
                            }
                        ],
                    });
                },
                removeValidationRule(index, length) {
                    this.formData.validation.splice(index, length);
                },
            }
        }
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

        $("#component_id").on("change", function () {
            let selectedForm = $("#form_id").val();
            let selectedComponent = $(this).val();
            if (selectedComponent && selectedForm) {
                axios.get('/form-builder/input-groups/' + selectedForm + '/' + selectedComponent)
                    .then(response => {
                        let parentComponents = response.data.data;
                        $("#group_id").empty();
                        $("#group_id").append($('<option>', {
                            text: 'Select Input Group'
                        }));
                        $("#group_id").empty();
                        $("#group_id").append($('<option>', {
                            text: 'Select Input Group'
                        }));
                        parentComponents.forEach(component => {
                            $("#group_id").append($('<option>', {
                                value: component.id,
                                text: component.label
                            }));
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    })
            } else {
                $("#group_id").empty();
                $("#group_id").append($('<option>', {
                    text: 'Select Input Group'
                }));
            }
        });

        CommonFunction.validForm('quickForm');
    </script>
@endsection


