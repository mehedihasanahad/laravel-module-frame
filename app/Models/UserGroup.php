<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_groups';
    protected $fillable = [
        'id',
        'name_en',
        'name_bn',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'ordering',
        'status'
        ];
}
