<?php

namespace App\Core\FormBuilder\Interfaces;

/**
 *
 */
interface FormServiceInterface
{
    /**
     * @param $processTypeId
     * @param $formType
     * @param $app_id
     * @return mixed
     */
    public static function formatFormData($processTypeId, $formType, $app_id = null): mixed;

    /**
     * @param $data
     * @param $parentId
     * @return array
     */
    public static function formatComponents($data, $parentId = null,): array;

    /**
     * @param $inputs
     * @return array
     */
    public static function formatInputs($inputs): array;

    /**
     * @param $processTypeId
     * @param $app_id
     * @param $formType
     * @return array
     */
    public static function formWithData($processTypeId, $app_id, $formType): array;

    /**
     * @param $components
     * @return array
     */
    public static function relatedDataModels($components): array;
}
