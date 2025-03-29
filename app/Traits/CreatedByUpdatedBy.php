<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait CreatedByUpdatedBy
{

    /**
     * Triggers when creating or updating  anything
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (Model $model) {
            $model->setAttribute('created_by', auth()->id());
        });
        static::updating(function (Model $model) {
            $model->setAttribute('updated_by', auth()->id());
        });
    }
}
