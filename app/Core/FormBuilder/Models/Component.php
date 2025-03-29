<?php

namespace App\Core\FormBuilder\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory, CreatedByUpdatedBy;

    protected $table = 'components';

    protected $primaryKey = 'id';

    protected const TEMPLATE_TYPE = [
        'two_column_grid' => 1,
        'four_column_grid' => 2,
        'tabular_form' => 3,
    ];

    protected $fillable = [
        'form_id',
        'parent_id', // refers to self primary key
        'title',
        'is_loop',
        'loop_data',
        'template_type', // 1 => 2 Column Grid ,2 => 4 Column Grid , 3 => Tabular Form
        'order',
        'step_no',
        'status', // 1 => active , 0 => inactive
    ];

    /**
     * A component has many inputs
     * @return HasMany
     */
    public function inputs(): HasMany
    {
        return $this->hasMany(Input::class, 'component_id', 'id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
