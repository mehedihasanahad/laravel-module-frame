<?php

namespace App\Core\Area\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'area_info';
    protected $guard_name = 'web';
    const Area = [
        'Division' => 1,
        'District' => 2,
        'Upzila' => 3,
    ];
    protected $fillable = array(
        'id',
        'area_nm',
        'pare_id',
        'area_type',
        'area_nm_ban',
        'nid_area_code',
        'sb_dist_code',
        'soundex_nm',
        'rjsc_id',
        'rjsc_name',
        'app_limit',
        'created_at',
        'updated_at'
    );

    protected $casts = [
        'area_type' => 'integer',
        'pare_id' => 'integer',
    ];
}
