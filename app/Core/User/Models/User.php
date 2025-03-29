<?php

namespace App\Core\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles,Notifiable;

    protected $table = 'users';
    protected $guard_name = 'web';
    protected $fillable = array(
        'desk_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'user_group_id',
        'is_approved',
        'status',
        'photo',
        'national_id',
        'birth_date',
        'present_address',
        'permanent_address',
        'division_id',
        'district_id',
        'upzila_id',
        'gender',
        'mobile',
        'nid',
        'login_otp',
        'auth_token',
        'otp_expire_time',
        'created_at',
        'updated_at'
    );

}
