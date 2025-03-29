<?php

namespace App\Core\ProcessEngine\Http\Controllers\Api\v1;

use App\Core\ProcessEngine\Models\ProcessStatus;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class GlobalApiController extends Controller
{
    use ApiResponse;

    public function processStatus($processTypeId)
    {
        $processStatuses = ProcessStatus::query()->active()->processType($processTypeId)->get(['id','status_name']);
        return $this->successResponse($processStatuses, 'Process Status fetched successfully');
    }
}
