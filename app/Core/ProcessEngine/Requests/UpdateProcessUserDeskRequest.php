<?php

namespace App\Core\ProcessEngine\Requests;

use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProcessUserDeskRequest extends FormRequest
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
    public function rules(): array
    {

        return [
            'name' => 'required|unique:process_user_desks,name,'.Encryption::decodeId($this->process_user_desk),
            'status' => 'required'
        ];
    }
}
