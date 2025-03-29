<?php

namespace App\Core\FormBuilder\Models;


use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Input extends Model
{
    use CreatedByUpdatedBy;

    protected $table = 'inputs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_id',
        'component_id',
        'label',
        'label_position', //1=>Upper,2=>side
        'width', //1=>Default,2=>Full Row
        'group_id',
        'input_tag_name',
        'is_loop',
        'loop_data',
        'input_id',
        'name',
        'model_namespace',
        'column_name',
        'order',
        'status',
        'attribute_bag',
        'validation',
    ];

    protected $casts = [
        'validation' => 'array',
    ];

    protected function attributeBag(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
        );
    }

    public function group(): HasOne
    {
        return $this->hasOne(InputGroup::class, 'id', 'group_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

}
