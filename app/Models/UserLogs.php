<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
    protected $table = 'user_logs';
    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'access_log_id',
        'login_dt',
        'logout_dt',
        'created_at',
        'updated_at'
    ];
}
