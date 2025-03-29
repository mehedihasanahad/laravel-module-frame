<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caption',
        'email_subject',
        'email_content',
        'email_active_status',
        'email_cc',
        'sms_content',
        'sms_active_status',
        'is_archive',
    ];
}
