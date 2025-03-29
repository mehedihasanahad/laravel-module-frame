<?php

namespace App\Core\ProcessEngine\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProcessRequest extends FormRequest
{
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
    public function rules(Request $request): array
    {
        return [
            'process_status_id' => 'required',
            'remarks' => $request->get('is_remarks_required') == 1 ? 'required' : '',
            'attach_file' => $request->get('is_file_required') == 1 ? 'required' : '',
        ];
    }

    public function messages(): array
    {
        return [
            'status_id.required' => 'Apply Status Field Is Required',
            'remarks.required' => 'Remarks Field Is Required',
            'attach_file.required' => 'Attach File Field Is Required',
        ];
    }

}
