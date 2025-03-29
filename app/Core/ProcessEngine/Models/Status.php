<?php

namespace App\Core\ProcessEngine\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
    ];
}
