@php
    $isInLine = $input['label_position'] === 2;
@endphp
<div @class([ 'form-group','form-group row' => $isInLine])>
    <label @class(['col-md-4' => $isInLine,'d-none'=> isset($label)])> {{$input['label']}}</label>
    <div @class(['col-md-8' => $isInLine])>
        <textarea  id="{{$input['input_id']}}" name="{{$input['name']}}" {!! $input['attribute_bag']!!}>
           {{ $input['attributes']['value'] ?? '' }}
        </textarea>
        @error($input['name'])
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
</div>
