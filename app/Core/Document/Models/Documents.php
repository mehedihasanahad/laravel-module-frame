<?php

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    protected $table = 'doc_name';
    protected $fillable = array(
        'id',
        'name',
        'simple_file',
        'status',
        'max_size',
        'min_size',
        'created_by',
        'is_archive',
        'created_at',
        'updated_at',
        'updated_by',
    );
}
