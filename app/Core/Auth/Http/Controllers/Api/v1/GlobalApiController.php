<?php

namespace App\Core\Auth\Http\Controllers\Api\v1;

use App\Core\Auth\Models\AreaInfo;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class GlobalApiController extends Controller
{
    use ApiResponse;

    /**
     * Get division wise districts
     * Get division wise districts
     * @param $divisionId
     * @return JsonResponse
     */
    public function getDistricts($divisionId)
    {

        try {
            $districts = AreaInfo::query()
                ->where([
//                    ['area_type', '=', AreaInfo::AreaType['Division']],
                    ['pare_id', '=', $divisionId,]
                ])
                ->get(['id', 'area_nm']);
            return $this->successResponse($districts, 'Division Fetched Successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get district wise upzilas
     * @param $districtId
     * @return JsonResponse
     */
    public function getUpzilas($districtId)
    {
        try {
            $upzilas = AreaInfo::query()
                ->where([
//                    ['area_type', '=', AreaInfo::AreaType['Division']],
                    ['pare_id', '=', $districtId],
                ])
                ->get(['id', 'area_nm']);
            return $this->successResponse($upzilas, 'Upzilas Fetched Successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Some thing went wrong');

        }
    }
}
