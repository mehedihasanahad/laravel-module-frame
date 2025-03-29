@php
    $isInLine = $input['label_position'] === 2;
@endphp
<div @class([ 'form-group','row' => $isInLine])>
    @if( Arr::exists($input['attributes'], 'type')
    && in_array( $input['attributes']['type'] ,["checkbox","radio"] ))
        <input id="{{$input['input_id']}}" name="{{$input['name']}}" {!! $input['attribute_bag']!!}/>
        <label @class(['ml-1','col-md-4' => $isInLine])> {{$input['label']}}</label>
        <br/>
        @error($input['name'])
        <span class="text-danger">{{$message}}</span>
        @enderror
    @else
        <label @class(['col-md-4' => $isInLine,'d-none'=> isset($label)])> {{$input['label']}}</label>
        <div @class(['col-md-8' => $isInLine])>
            <input id="{{$input['input_id']}}" name="{{$input['name']}}" {!! $input['attribute_bag']!!}/>
            @if(Str::contains($input['name'],'[]'))
                @error(Str::replace('[]','',$input['name']).'.*')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            @else
                @error($input['name'])
                <span class="text-danger">{{ $message }}</span>
                @enderror
            @endif
        </div>
    @endif
</div>
