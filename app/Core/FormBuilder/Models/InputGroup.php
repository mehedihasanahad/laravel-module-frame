<?php

namespace App\Core\FormBuilder\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InputGroup extends Model
{
    use CreatedByUpdatedBy;

    protected $primaryKey = 'id';
    protected $table = 'input_groups';
    protected $fillable = [
        'form_id',
        'component_id',
        'step_no',
        'label',
        'value'
    ];

    public function form(): BelongsTo {
        return $this->belongsTo(Form::class);
    }

    public function component(): BelongsTo {
        return $this->belongsTo(Component::class);
    }

}
