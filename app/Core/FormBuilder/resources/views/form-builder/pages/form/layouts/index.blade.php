@if( in_array(request()->route()->getName(), ['application.create','application.edit'])  )
    @inject('encryption','\App\Libraries\Encryption')
    @inject('formService','\App\Core\FormBuilder\Services\FormService')
    @php
        $processTypeId = Route::getFacadeRoot()->current()->parameters['process_type_id'];
        $formType = Route::getFacadeRoot()->current()->parameters['form_type'];
        $appId = request()->route()->parameters()['app_id'] ?? null;
        $decodedProcessTypeId = $encryption::decodeId($processTypeId);
        $decodedFormType = $encryption::decodeId($formType);
        $decodedAppId = null;
        if ($appId!==null)
            $decodedAppId = $encryption::decodeId($appId);
        $form = $formService::formatFormData($decodedProcessTypeId, $decodedFormType, $decodedAppId);
        $app = $form->form_data_json;
    @endphp
    @include('FormBuilder::form-builder.pages.form.sub-views.form.form')
@else
    @include('FormBuilder::form-builder.pages.view.view')
@endif

