<?php

namespace App\Core\FormBuilder\Models;

use App\Core\ProcessEngine\Models\ProcessType;
use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Form extends Model
{
    use HasFactory, CreatedByUpdatedBy;

    protected $table = 'forms';

    protected $primaryKey = 'id';

    protected $fillable = [
        'process_type_id', // process type id
        'form_type', // 1 => add form, 2 => edit form
        'template_type', //1 => Default,2 => Steps
        'title',
        'form_id',
        'steps', // number of steps
        'steps_name',
        'app_model_namespace',
        'app_id',
        'form_data_json', // in key value pair , value is a raw sql query
        'method', // form submit method
        'action', // form submit action url
        'autocomplete',
        'enctype',
        'status', // 1 => active,2 =>inactive
    ];

    protected $casts = [
        'form_data_json' => 'array',
    ];

    /**
     * Form has many components
     * @return HasMany
     */
    public function components(): HasMany
    {
        return $this->hasMany(Component::class, 'form_id', 'id');
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(Input::class, 'form_id');
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(ProcessType::class, 'process_type_id', 'id');
    }

    public function scopeActive($query)
    {
        return $this->where('status', 1);
    }
}
