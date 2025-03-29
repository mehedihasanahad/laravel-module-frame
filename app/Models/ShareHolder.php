<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareHolder extends Model
{
    use CreatedByUpdatedBy;

    protected $table = 'shareholders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'app_id',
        'process_type_id',
        'name',
        'nationality',
        'nid',
        'passport',
        'dob',
        'designation',
        'mobile',
        'email',
        'image',
        'no_of_share',
        'share_value',
        'share_percent',
    ];
}
