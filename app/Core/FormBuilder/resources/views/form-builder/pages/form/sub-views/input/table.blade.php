<style>
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
        height: 100%;
        text-align: center;
        vertical-align: middle;
    }
</style>
<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>#</th>
        @foreach ($component['inputs'] as $input)
            <th>{{$input['label']}}</th>
        @endforeach
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    @if( !empty($component['loop_data'])  )
        @foreach($form->form_data_json[$component['loop_data']] as $value)
            <tr>
                <td>{{$loop->iteration}}</td>
                @foreach ($component['inputs'] as $input)
                    @php
                        if (Arr::exists($input['attribute_bag'], 'value')) {
                            $val = '';
                            eval("\$val=" . $input['attribute_bag']['value'] . ';');
                            $input['attribute_bag'] = collect($input['attribute_bag'])->put('value', $val);
                        }
                        $input['attributes'] = $input['attribute_bag'];
                        $input['attribute_bag'] = collect($input['attribute_bag'])
                                                  ->map(fn($value,$key)=>$key . ' = "' . $value . '"')
                                                  ->implode(' ');
                    @endphp
                    <td>
                        @switch($input['input_tag_name'])
                            @case('input')
                                @include('FormBuilder::form-builder.pages.form.sub-views.input.input',['label'=>false])
                                @break
                            @case('select')
                                @include('FormBuilder::form-builder.pages.form.sub-views.input.select',['label'=>false])
                                @break
                            @case('textarea')
                                @include('FormBuilder::form-builder.pages.form.sub-views.input.textarea',['label'=>false])
                                @break
                            @case('group_inputs')
                                @include('FormBuilder::form-builder.pages.form.sub-views.input.input_group')
                                @break
                            @default
                                404 element not found
                        @endswitch
                    </td>
                @endforeach
                <td>
                    @if($loop->iteration === 1)
                        <button class="btn btn-sm btn-success rounded-circle" type="button"
                                onclick="addRowTable(this.parentElement.parentElement)">
                            <i class="fas fa-plus"></i>
                        </button>
                    @else
                        <button class="btn btn-sm btn-success rounded-circle" type="button"
                                onclick="removeRow(this.parentElement.parentElement)">
                            <i class="fas fa-minus"></i>
                        </button>
                    @endif

                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>1</td>
            @foreach ($component['inputs'] as $input)
                @php
                    if (Arr::exists($input['attribute_bag'], 'value')) {
                        $val = '';
                        eval("\$val=" . $input['attribute_bag']['value'] . ';');
                        $input['attribute_bag'] = collect($input['attribute_bag'])->put('value', $val);
                    }

                    $input['attribute_bag'] = collect($input['attribute_bag'])
                        ->map(function ($value, $key) {
                            return $key . ' = "' . $value . '"';
                        })->implode(' ');
                @endphp
                <td>
                    @switch($input['input_tag_name'])
                        @case('input')
                            @include('FormBuilder::form-builder.pages.form.sub-views.input.input',['label'=>false])
                            @break
                        @case('select')
                            @include('FormBuilder::form-builder.pages.form.sub-views.input.select',['label'=>false])
                            @break
                        @case('textarea')
                            @include('FormBuilder::form-builder.pages.form.sub-views.input.textarea',['label'=>false])
                            @break
                        @case('group_inputs')
                            @include('FormBuilder::form-builder.pages.form.sub-views.input.input_group')
                            @break
                        @default
                            404 element not found
                    @endswitch
                </td>
            @endforeach
            <td>
                <button class="btn btn-sm btn-success rounded-circle" type="button"
                        onclick="addRowTable(this.parentElement.parentElement)">
                    <i class="fas fa-plus"></i>
                </button>
            </td>
        </tr>
    @endif
    </tbody>
</table>
