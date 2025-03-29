<?php

namespace App\Core\ProcessEngine\Models;

use App\Core\Document\Models\ProcessType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessPath extends Model
{
    protected $table = 'process_paths';
    protected $primaryKey = 'id';
    protected $fillable = [
        'process_type_id',
        'desk_from',
        'desk_to',
        'status_from',
        'status_to',
        'file_attachment',
        'remarks',
    ];

    public function processType(): BelongsTo
    {
        return $this->belongsTo(ProcessType::class, 'process_type_id', 'id');
    }

    public function deskFrom(): BelongsTo
    {
        return $this->belongsTo(ProcessUserDesk::class, 'desk_from', 'id');
    }

    public function deskTo(): BelongsTo
    {
        return $this->belongsTo(ProcessUserDesk::class, 'desk_to', 'id');
    }

    public function statusFrom(): BelongsTo
    {
        return $this->belongsTo(ProcessStatus::class, 'status_from', 'id');
    }

    public function statusTo(): BelongsTo
    {
        return $this->belongsTo(ProcessStatus::class, 'status_to', 'id');
    }
}
