<?php

namespace App\Core\Auth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model {

    protected $table = 'failed_login_history';
    protected $fillable = array(
        'remote_address',
        'user_email',
        'is_archived',
        'created_at',
        'updated_at'
    );

    public function user()
    {
        return $this->belongsTo(User::class,'user_email','email');
    }
}
