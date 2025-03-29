<?php

namespace App\Core\FormBuilder\Interfaces;

/**
 *
 */
interface FormActionInterface
{
    /**
     * @param $process
     * @param $processTypeId
     * @param $appId
     * @param $action
     * @return void
     */
    public static function storeProcessInfo($process, $processTypeId, $appId, $action) :void;


    /**
     * @param $model
     * @param $attributes
     * @param $folderName
     * @return mixed
     */
    public static function storeApplicationInfo($model, $attributes, $folderName): mixed;

    /**
     * @param $model
     * @param $attributes
     * @param $dKey
     * @param $folderName
     * @param $applicationId
     * @param $relatedColumnName
     * @return void
     */
    public static function storeApplicationRelatedInfo($model, $attributes, $dKey, $folderName, $applicationId, $relatedColumnName): void;


}
