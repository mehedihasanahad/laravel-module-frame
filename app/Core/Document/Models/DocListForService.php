<?php

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocListForService extends Model
{
    use HasFactory;

    protected $table = 'doc_list_for_service';
    protected $fillable = array(
        'id',
        'process_type_id',
        'doc_id',
        'doc_type_for_service_id',
        'order',
        'is_required',
        'autosuggest_status',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

    protected $casts = [
        'doc_id' => 'integer',
        'process_type_id' => 'integer',
        'is_required' => 'integer',
        'autosuggest_status' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];
}
