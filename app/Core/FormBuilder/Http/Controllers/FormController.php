<?php

namespace App\Core\FormBuilder\Http\Controllers;

use App\Core\Document\Models\ProcessList;
use App\Core\FormBuilder\Actions\FormAction;
use App\Core\FormBuilder\Interfaces\FormControllerInterface;
use App\Core\FormBuilder\Http\Requests\FormStoreRequest;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

/**
 * Loads all form data and returns the form
 */
class FormController extends Controller implements FormControllerInterface
{
    /**
     * @param FormStoreRequest $request
     * @return RedirectResponse
     */
    public function store(FormStoreRequest $request): RedirectResponse
    {
        $encodedParameters = request()->route()->parameters();
        $decodedParameters = decodeRoutePrams($encodedParameters);
        $decodedProcessTypeId = $decodedParameters['process_type_id'];
        $decodedFormType = $decodedParameters['form_type'];

        $form = formWithInputs($decodedProcessTypeId, $decodedFormType);

        $formattedData = formattedInputsByModelNamespace($form->inputs, $request);
        $reorderedArray = [];

        if (isset($formattedData[$form->app_model_namespace])) {
            $reorderedArray = [
                    $form->app_model_namespace => $formattedData[$form->app_model_namespace],
                ] + array_diff_key($formattedData, [$form->app_model_namespace => '']);
        }

        $modelInstance = null;

        foreach ($reorderedArray as $modelNameSpace => $attributes) {
            $folderName = Str::lower(Str::replace('\App\Models\\', '', $modelNameSpace));
            $collection = collect($attributes)
                ->filter(fn($attribute) => is_array($attribute[1]) && !empty($attribute[1]))
                ->toArray();

            if (count($attributes) !== count($collection))
                $collection = [];

            if (is_array($collection) && !empty($collection)) {
                foreach ($attributes[0][1] as $dKey => $data) {
                    $model = new $modelNameSpace();
                    FormAction::storeApplicationRelatedInfo($model,$attributes,$dKey,$folderName,$modelInstance->id,$form->app_id);
                }
            } else {
                $model = new $modelNameSpace();
                $modelInstance = FormAction::storeApplicationInfo($model,$attributes,$folderName);
            }
        }

        $processList = new ProcessList();
        FormAction::storeProcessInfo($processList,$decodedProcessTypeId,$modelInstance->id,$request->actionBtn);

//        return redirect()->route('application.list', Encryption::encodeId($decodedProcessTypeId))->with('success', 'Form submitted successfully');
    }

    /**
     * @param FormStoreRequest $request
     * @return RedirectResponse
     */
    public function update(FormStoreRequest $request): RedirectResponse
    {
        $encodedParameters = request()->route()->parameters();
        $decodedParameters = decodeRoutePrams($encodedParameters);
        $decodedProcessTypeId = $decodedParameters['process_type_id'];
        $decodedFormType = $decodedParameters['form_type'];
        $decodedAppId = $decodedParameters['app_id'];

        $form = formWithInputs($decodedProcessTypeId, $decodedFormType);

        $formattedData = formattedInputsByModelNamespace($form->inputs, $request);

        $reorderedArray = [];

        if (isset($formattedData[$form->app_model_namespace])) {
            $reorderedArray = [
                    $form->app_model_namespace => $formattedData[$form->app_model_namespace],
                ] + array_diff_key($formattedData, [$form->app_model_namespace => '']);
        }

        $modelInstance = null;

        foreach ($reorderedArray as $modelNameSpace => $attributes) {

            $folderName = Str::lower(Str::replace('\App\Models\\', '', $modelNameSpace));

            $collection = collect($attributes)
                ->filter(fn($attribute) => !empty($attribute) && is_array($attribute[1])  && !empty($attribute[1]) )
                ->toArray();

            if (count($attributes) !== count($collection))
                $collection = [];

            if (is_array($collection) && !empty($collection)) {
                $modelNameSpace::query()->where($form->app_id, $decodedAppId)->delete();
                foreach ($attributes[0][1] as $dKey => $data) {
                    $model = new $modelNameSpace();
                    FormAction::storeApplicationRelatedInfo($model,$attributes,$dKey,$folderName,$modelInstance->id,$form->app_id);
                }
            } else {
                $model = $modelNameSpace::where('id', $decodedAppId)->first();
                $modelInstance = FormAction::storeApplicationInfo($model,$attributes,$folderName);
            }
        }

        $processList = ProcessList::query()
            ->where('ref_id', $decodedAppId)
            ->where('process_type_id', $decodedProcessTypeId)
            ->first();

        FormAction::storeProcessInfo($processList,$decodedProcessTypeId,$modelInstance->id,$request->actionBtn);

        return redirect()->route('application.list', Encryption::encodeId($decodedProcessTypeId))->with('success', 'Form updated successfully');
    }
}
