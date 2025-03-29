@inject('formService','\App\Core\FormBuilder\Services\FormService')
@php
    $encodedParameters = request()->route()->parameters();
    $decodedParameters = decodeRoutePrams($encodedParameters);
    $decodedProcessTypeId = $decodedParameters['process_type_id'];
    $decodedAppId = $decodedParameters['app_id'];
    $decodedFormType = $decodedParameters['form_type'];

    $formWithData = $formService::formWithData($decodedProcessTypeId,$decodedAppId,$decodedFormType);
    $form = $formWithData['form'];
    $mainModelData = $formWithData['mainModelData'];
    $relatedModelData = $formWithData['relatedModelData'];
@endphp
<section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">{{$form->title}}</h3>
            </div>
            <div class="card-body">
                @include('FormBuilder::form-builder.pages.view.sub-views.component.main-component')
            </div>
        </div>
    </div>
</section>
