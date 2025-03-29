@php
     $components = $form->formatted;
@endphp
@foreach($components as $component)
    @include('FormBuilder::form-builder.pages.view.sub-views.component.component',['component' => $component])
@endforeach


