<?php

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Model;

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
}
