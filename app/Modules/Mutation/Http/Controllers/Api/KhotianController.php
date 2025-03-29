<?php

namespace App\Modules\Mutation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class KhotianController extends Controller
{
    use ApiResponse;
    public function __invoke($type)
    {
        $data = [
            [
                'id' => 1,
                'khotian_no' => 1234,
                'dag_no' => 1234,
                'dolil_no' => 3211,
                'dolil_date' => now()->format('Y-m-d'),
            ],
            [
                'id' => 2,
                'khotian_no' => 44557,
                'dag_no' => 44557,
                'dolil_no' => 44557,
                'dolil_date' => now()->addDay(100)->format('Y-m-d'),
            ]
        ];

        $searchData = collect($data)->firstWhere('id',$type);

        return $this->successResponse($searchData,'Data fetched successfully');
    }
}
