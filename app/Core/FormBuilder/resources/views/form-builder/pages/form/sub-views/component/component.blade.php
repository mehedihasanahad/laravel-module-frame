@php
    $isChildComponent = isset($component['parent_id']);
    $componentId = $isChildComponent ? 'component_' . $component['parent_id'] . '_' . $component['id'] : 'component_' . $component['id'];
@endphp

<div class="{{ $componentId }}" id="{{ $componentId }}">
    <div class="card card-success" id="{{\Str::slug($component['title']).'-'. time()}}">
        <div class="card-header">
            <h3 class="card-title">{{$component['title']}}</h3>
            <div class="card-tools">
                {{--        @dump($loop->index,$loop->parent->index)--}}
                @if($component['is_loop'] === 1)
                    @if($loop->iteration === 1)
                        <button type="button" class="btn btn-tool" id="addRowButton"
                                onclick="addRow(this.parentElement.parentElement.parentElement)">
                            <i class="fas fa-plus"></i>
                        </button>
                    @else
                        <button type="button" class="btn btn-tool" id="addRowButton"
                                onclick="removeRow(this.parentElement.parentElement.parentElement)">
                            <i class="fas fa-minus"></i>
                        </button>
                    @endif
                @endif
            </div>
        </div>
        <div class="card-body">
            @if (isset($component['inputs']) && count($component['inputs']) > 0)
                <div class="row">
                    @switch($component['template_type'])
                        @case(1)
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
                                <div @class(['col-md-6' => $input['width'] === 1,'col-md-12' =>$input['width'] === 2 ])>
                                    @switch($input['input_tag_name'])
                                        @case('input')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.input')
                                            @break
                                        @case('select')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.select')
                                            @break
                                        @case('textarea')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.textarea')
                                            @break
                                        @case('group_inputs')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.input_group')
                                            @break
                                        @default
                                            404 element not found
                                    @endswitch
                                </div>
                            @endforeach
                            @break
                        @case(2)
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
                                <div class="col-sm-3">
                                    @switch($input['input_tag_name'])
                                        @case('input')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.input')
                                            @break
                                        @case('select')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.select')
                                            @break
                                        @case('textarea')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.textarea')
                                            @break
                                        @case('group_inputs')
                                            @include('FormBuilder::form-builder.pages.form.sub-views.input.input_group')
                                            @break
                                        @default
                                            404 element not found
                                    @endswitch
                                </div>
                            @endforeach
                            @break
                        @case(3)
                            @include('FormBuilder::form-builder.pages.form.sub-views.input.table')
                            @break
                        @default
                            @include('FormBuilder::form-builder.pages.form.sub-views.input.default')
                    @endswitch
                </div>
            @endif
            @if (isset($component['components']) && count($component['components']) > 0)
                @foreach ($component['components'] as $key => $childComponent)
                    @if( $childComponent['is_loop'] === 1 && !empty($childComponent['loop_data'])  )
                        {{--                        checking if component can be looped and has existing values--}}
                        @foreach($form->form_data_json[$childComponent['loop_data']] as  $value)
                            @include('FormBuilder::form-builder.pages.form.sub-views.component.component',['component' => $childComponent])
                        @endforeach
                    @else
                        @include('FormBuilder::form-builder.pages.form.sub-views.component.component',['component' => $childComponent])
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
