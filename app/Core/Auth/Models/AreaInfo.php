<?php

namespace App\Core\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AreaInfo extends Model
{
    protected $table = 'area_info';
    protected $primaryKey = 'id';

    /**
     * Area Types
     */

    const AreaType = [
        'Division' => 1,
        'District' => 2,
        'Upzila' => 3,
    ];

    protected $fillable = [
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
    ];
}
