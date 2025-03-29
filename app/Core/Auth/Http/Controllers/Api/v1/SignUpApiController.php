<?php

namespace App\Core\Auth\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Core\Auth\Models\AreaInfo;

class SignUpApiController extends Controller
{
    public function getDivision()
    {
        $divisions = AreaInfo::query()
            ->where('area_type', AreaInfo::AreaType['Division'])
            ->get(['id', 'area_nm']);

        return response()->json(['divisions' => $divisions]);
        
    }

}