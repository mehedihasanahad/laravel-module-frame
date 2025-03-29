@php
    $isInLine = $input['label_position'] === 2;
@endphp
<div @class([ 'form-group','form-group row' => $isInLine])>
    <label @class(['col-md-4' => $isInLine,'d-none'=> isset($label)])>{{$input['label']}}</label>
    <div @class(['col-md-8' => $isInLine])>
        <select id="{{$input['input_id']}}" name="{{$input['name']}}" {!! $input['attribute_bag']!!}>
            <option value="{{null}}"> Select {{$input['label']}}</option>
            @foreach($form->form_data_json[$input['loop_data']] ?? [] as $value)
                <option
                    value="{{ array_values($value)[0] }}"
                    @if (isset($input['attributes']['value']) && $input['attributes']['value'] == array_values($value)[0])
                        selected
                    @endif
                >{{ array_values($value)[1] }}</option>
            @endforeach
        </select>
        @error($input['name'])
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
</div>

