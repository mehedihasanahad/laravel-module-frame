<?php

namespace App\Modules\VSAT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VSAT extends Model
{
    protected $table = 'vsat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'company_name',
        'company_address',
        'applicant_name',
        'applicant_division',
        'applicant_gender',
        'applicant_email',
    ];
}
