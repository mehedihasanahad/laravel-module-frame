@php
    $isChildComponent = isset($component['parent_id']);
    $componentId = $isChildComponent ? 'component_' . $component['parent_id'] . '_' . $component['id'] : 'component_' . $component['id'];
@endphp

<div class="{{ $componentId }}" id="{{ $componentId }}">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">{{$component['title']}}</h3>
        </div>
        <div class="card-body">
            @if (isset($component['inputs']) && count($component['inputs']) > 0)
                <div class="row">
                    @switch($component['template_type'])
                        @case(1)
                            @if($component['is_loop'] === 1 &&  $component['template_type'] !== 3 )
                                @php
                                    $dynamicArrayKey = collect($component['inputs'])->first()['model_namespace'];
                                @endphp
                                @foreach($relatedModelData[$dynamicArrayKey] as $data)
                                    @if($iteration === $loop->iteration)
                                        @foreach ($component['inputs'] as $input)
                                            <div @class([ 'row','col-md-6' => $input['width'] === 1,'col-md-12' =>$input['width'] === 2 ])>
                                                <label class="col-md-3"> {{$input['label']}}</label>
                                                <div class="col-md-9">
                                                    {{$data[$input['column_name']]}}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                @foreach ($component['inputs'] as $input)
                                    <div @class([ 'row','col-md-6' => $input['width'] === 1,'col-md-12' => $input['width'] === 2 ])>
                                        <label class="col-md-3"> {{$input['label'] }}</label>
                                        <div class="col-md-9">
                                            <span> {{$mainModelData[$input['column_name']]}}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @break
                        @case(2)
                            @foreach ($component['inputs'] as $input)
                                <div @class(['row', 'col-md-3' => $input['width'] === 1,'col-md-12' =>$input['width'] === 2 ])>
                                    <label class="col-md-4"> {{$input['label'] ?? $input['group_label'] }}</label>
                                    <span class="col-md-8"> {{$mainModelData[$input['column_name']]}}</span>
                                </div>
                            @endforeach
                            @break
                        @case(3)
                            @include('FormBuilder::form-builder.pages.view.sub-views.component.table')
                            @break
                        @default
                            @include('FormBuilder::form-builder.sub-views.input.default')
                    @endswitch
                </div>
            @endif
            @if (isset($component['components']) && count($component['components']) > 0)
                @foreach ($component['components'] as $key => $childComponent)
                    @if( $childComponent['is_loop'] === 1  )
                        @php
                            $dynamicArrayKey = collect($childComponent['inputs'])->first()['model_namespace'];
                        @endphp
                        @foreach($relatedModelData[$dynamicArrayKey] as  $value)
                            @include('FormBuilder::form-builder.pages.view.sub-views.component.component',[ 'iteration' => $loop->iteration, 'component' => $childComponent])
                        @endforeach
                    @else
                        @include('FormBuilder::form-builder.pages.view.sub-views.component.component',['component' => $childComponent])
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
