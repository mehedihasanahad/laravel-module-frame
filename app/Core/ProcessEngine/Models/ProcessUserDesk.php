<?php

namespace App\Core\ProcessEngine\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class ProcessUserDesk extends Model
{
    use CreatedByUpdatedBy;

    protected $table = 'process_user_desks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
