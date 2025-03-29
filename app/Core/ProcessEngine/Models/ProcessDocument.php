<?php

namespace App\Core\ProcessEngine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessDocument extends Model
{
    use HasFactory;
    protected $table = 'process_documents';

    protected $fillable = array(
        'id',
        'process_type_id',
        'ref_id',
        'process_desk_id',
        'process_status_id',
        'file',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );
}
