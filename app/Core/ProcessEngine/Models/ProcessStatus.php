<?php

namespace App\Core\ProcessEngine\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessStatus extends Model
{
    use CreatedByUpdatedBy;

    protected $table = 'process_statuses';
    protected $fillable = [
        'id',
        'process_type_id',
        'status_name',
        'color',
        'status',
        'addon_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function processType(): BelongsTo
    {
        return $this->belongsTo(ProcessType::class, 'process_type_id', 'id');
    }

    public function scopeProcessType($query, $process_type_id)
    {
        return $query->where('process_type_id', $process_type_id);
    }

    public function scopeActive($query,)
    {
        return $query->where('status', 1);
    }
}
