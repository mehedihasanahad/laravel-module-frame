<?php

namespace App\Core\FormBuilder\Actions;

use App\Core\FormBuilder\Models\Input;
use App\Libraries\Encryption;

class InputAction
{
    /**
     * @param $request
     * @param $input
     * @return mixed
     */
    public static function save($request, $input): mixed
    {
        $data = $request->validated();
        $validation = collect($data['validation']);
        $attributes = collect($data['attribute_bag'])->mapWithKeys(fn($value) => [$value['key'] => $value['value']]);
        $validationRules = $validation->pluck('rules')->collapse()->pluck('rule');
        $validationMessages = $validation->pluck('message')->collapse()->mapWithKeys(fn($value) => [$value['key'] => $value['value']]);
        $data['validation'] = ['rules' => $validationRules, 'message' => $validationMessages];
        $data['attribute_bag'] = $attributes;
        $input->fill($data)->save();
        return $input;
    }

    public static function update($request, $id): mixed
    {
        $input = Input::query()->findOrFail(Encryption::decodeId($id));
        $data = $request->validated();
        $validation = collect($data['validation']);
        $attributes = collect($data['attribute_bag'])->mapWithKeys(fn($value) => [$value['key'] => $value['value']]);
        $validationRules = $validation->pluck('rules')->collapse()->pluck('rule');
        $validationMessages = $validation->pluck('message')->collapse()->mapWithKeys(fn($value) => [$value['key'] => $value['value']]);
        $data['validation'] = ['rules' => $validationRules, 'message' => $validationMessages];
        $data['attribute_bag'] = $attributes;
        $input->fill($data)->save();
        return $input;
    }

}
