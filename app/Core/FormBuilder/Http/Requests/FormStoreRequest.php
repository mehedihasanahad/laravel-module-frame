<?php

namespace App\Core\FormBuilder\Http\Requests;

use App\Core\FormBuilder\Models\Form;
use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class FormStoreRequest extends FormRequest
{
    private array $rules;
    private array $messages;

    public function __construct()
    {
        $processTypeId = Route::getFacadeRoot()->current()->parameters['process_type_id'];;
        $formType = Route::getFacadeRoot()->current()->parameters['form_type'];

        $decodedProcessTypeId = Encryption::decodeId($processTypeId);
        $decodedFormType = Encryption::decodeId($formType);
        $form = Form::query()
            ->where([
                'process_type_id' => $decodedProcessTypeId,
                'form_type' => $decodedFormType
            ])
            ->with(['inputs' => function ($q) {
                return $q->where('status', 1)
                    ->orderBy('order', 'ASC');
            }])
            ->first();

        if (request()->actionBtn === 'submit'){
            $this->rules = collect($form->inputs)->map(fn($input) => [
                str_replace('[]', '', $input->name) => $input->validation['rules'],
            ])
                ->collapse()
                ->toArray();

            $this->messages = collect($form->inputs)->map(fn($input) => $input->validation['message'])
                ->collapse()
                ->toArray();
        } else{
            $this->rules = [];
            $this->messages = [];
        }



    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
