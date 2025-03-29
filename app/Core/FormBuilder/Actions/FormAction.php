<?php

namespace App\Core\FormBuilder\Actions;

use App\Core\Document\Models\ProcessType;
use App\Core\FormBuilder\Interfaces\FormActionInterface;
use App\Libraries\CommonFunction;

class FormAction implements FormActionInterface
{

    public static function storeProcessInfo($process, $processTypeId, $appId, $action): void
    {
        $processTypeName = ProcessType::query()->where('id', $processTypeId)->first()->module_folder_name ?? '';
        $process->process_type_id = $processTypeId;
        $process->org_id = 0;
        $process->process_user_desk_id = ($action === 'submit') ? 1 : 0;
        $process->ref_id = $appId;
        $process->tracking_no = $process->tracking_no ?: uniqid($processTypeName . '-');
        $process->json_object = json_encode([
            "Applicant Name" => auth()->user()->name ?? '',
            "Email" => auth()->user()->email ?? '',
            "Phone" => auth()->user()->mobile ?? '',
        ]);
        $process->process_status_id = ($action === 'submit') ? 1 : -1;
        $process->user_id = 0;
        $process->read_status = 0;
        $process->previous_hash = 0;
        $process->hash_value = 0;
        $process->remarks = 0;
        $process->save();
    }

    public static function storeApplicationInfo($model, $attributes, $folderName): mixed
    {
        foreach ($attributes as $attribute) {
            if (is_array($attribute[1])) {
                $processedValues = array_map(function ($value) use ($folderName) {
                    if (is_file($value)) {
                        return CommonFunction::resizeImageAndMoveToDirectories($value, 'uploads/' . $folderName, 200, 200, 'img')['imagePath'];
                    }
                    return $value;
                }, $attribute[1]);

                $model->{$attribute[0]} = implode(' | ', $processedValues);
            } else {
                if (is_file($attribute[1])) {
                    $model->{$attribute[0]} = CommonFunction::resizeImageAndMoveToDirectories($attribute[1], 'uploads' . '/' . $folderName, 200, 200, 'img')['imagePath'];
                } else {
                    $model->{$attribute[0]} = $attribute[1];
                }
            }
        }
        $model->save();
        return $model;
    }

    public static function storeApplicationRelatedInfo($model, $attributes, $dKey, $folderName, $applicationId, $relatedColumnName): void
    {
        foreach ($attributes as $tData) {
            if (is_file($tData[1][$dKey])) {
                $model->{$tData[0]} = CommonFunction::resizeImageAndMoveToDirectories($tData[1][$dKey], 'uploads' . '/' . $folderName, 200, 200, 'img')['imagePath'];
            } else {
                $model->{$tData[0]} = $tData[1][$dKey];
                if ($relatedColumnName !== null) {
                    $model->{$relatedColumnName} = $applicationId;
                }
            }
        }
        $model->save();
    }
}
