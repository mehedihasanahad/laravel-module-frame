@php
    if ($current_step!==null){
            $components = collect($form->formatted)->where('step_no',$current_step);
    }
 else {
     $components = $form->formatted;
 }
@endphp
@foreach($components as $component)
    @if($current_step!==null)
        @if( $component['is_loop'] === 1 &&  !empty($component['loop_data']) &&$component['template_type'] !== 3 )
            @foreach($form->form_data_json[$component['loop_data']] as  $current_iteration => $value)
                @include('FormBuilder::form-builder.pages.form.sub-views.component.component',['current_step'=> $current_step ,'component' => $component])
            @endforeach
        @else
            @include('FormBuilder::form-builder.pages.form.sub-views.component.component',['current_step'=> $current_step ,'component' => $component])
        @endif
    @else
        @if($component['is_loop'] === 1 && !empty($component['loop_data']) && $component['template_type'] !== 3  )
            @foreach($form->form_data_json[$component['loop_data']] as $current_iteration => $value)
                @include('FormBuilder::form-builder.pages.form.sub-views.component.component',['current_step'=> null ,'component' => $component])
            @endforeach
        @else
            @include('FormBuilder::form-builder.pages.form.sub-views.component.component',['current_step'=> null ,'component' => $component])
        @endif
    @endif
@endforeach


