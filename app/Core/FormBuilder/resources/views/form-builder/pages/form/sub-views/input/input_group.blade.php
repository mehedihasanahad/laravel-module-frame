<div class="form-group">
    <label> {{$input['group_label']}} </label>
    <div>
        @foreach($input['grouped_inputs'] as $key => $groupedInputs)
            @php
                if (Arr::exists($groupedInputs['attribute_bag'], 'value') && Str::contains($groupedInputs['attribute_bag']['value'], '$')) {
                    $val = '';
                    eval("\$val=" . $groupedInputs['attribute_bag']['value'] . ';');
                    $groupedInputs['attribute_bag'] = collect($groupedInputs['attribute_bag'])->put('value', $val);
                }
                $attributes =  $groupedInputs['attribute_bag'];
                $groupedInputs['attribute_bag'] = collect($groupedInputs['attribute_bag'])->map(fn($value,$key)=>$key . ' = "' . $value . '"')
                                                   ->implode(' ');
                $columnValue = '';
                if (!empty($input['value']) && \Illuminate\Support\Str::contains($input['value'],'$'))
                    {
                        eval("\$columnValue=" . $input['value'] . ';');
                    }
            @endphp
            <div class="form-check form-check-inline">
                @switch($attributes['type'])
                    @case('radio')
                        <input id="{{$groupedInputs['input_id']}}"
                               name="{{$groupedInputs['name']}}" {!! $groupedInputs['attribute_bag']!!} {{ $columnValue == $attributes['value'] ? 'checked':'' }} >
                        @break
                    @case('checkbox')
                        @php
                            $columnValue = explode(' | ',$columnValue);
                        @endphp
                        <input id="{{$groupedInputs['input_id']}}"
                               name="{{$groupedInputs['name']}}" {!! $groupedInputs['attribute_bag']!!} {{ in_array($attributes['value'] ,$columnValue)  ? 'checked':'' }} >
                        @break
                    @default
                @endswitch
                <label class="form-check-label" for="{{$groupedInputs['input_id']}}">{{$groupedInputs['label']}}</label>
            </div>
        @endforeach
    </div>
</div>
@if(Str::contains($groupedInputs['name'],'[]'))
    @error(Str::replace('[]','',$groupedInputs['name']))
    <span class="text-danger">{{ $message }}</span>
    @enderror
@else
    @error($groupedInputs['name'])
    <span class="text-danger">{{ $message }}</span>
    @enderror
@endif
