<?php

namespace App\Core\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    public function index()
    {
        return view('Dashboard::index');
    }

    public function getChartData(Request $request)
    {
        $dataArr = [];
        $dataArr['pieData'] = $this->pieChartData();
        $dataArr['barData'] = $this->barChartData();
        $dataArr['lineData'] = $this->lineChartData();

        return response()->json(['responseCode'=> 1, 'data'=> $dataArr]);
    }

    private function pieChartData(){
    /*  ## Sample Data for PIE Structure
    *  $donutData = {
        labels: [
            'Chrome',
            'IE',
            'FireFox',
            'Safari',
            'Opera',
            'Navigator',
        ],
        datasets: [
            {
                data: [700,500,400,600,300,100],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }*/
        $returnData = [];
        $returnData['labels'] = ['Chrome','IE','FireFox','Safari','Opera','Navigator'];

        $dataVal = [700,500,400,600,300,100];
        $dataColors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];
        $dataArr = ['data'=>$dataVal,'backgroundColor'=>$dataColors];
        $returnData['datasets'][] = $dataArr;
        return json_encode($returnData);
    }

    private function barChartData(){
    /*  ## Sample Data for BAR Structure
    var areaChartData = {
        labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                label               : 'Digital Goods',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [28, 48, 40, 19, 86, 27, 90]
            },
            {
                label               : 'Electronics',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : [65, 59, 80, 81, 56, 55, 40]
            },
        ]
    }
    */
        $returnData = [];
        $returnData['labels'] = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];

        $dataSetObj = [
            [
                'label' => 'Digital Goods',
                'backgroundColor' => 'rgba(60,141,188,0.9)',
                'borderColor' => 'rgba(60,141,188,0.8)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(60,141,188,1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(60,141,188,1)',
                'data' => [28, 48, 40, 19, 86, 27, 90]
            ],
            [
                'label' => 'Electronics',
                'backgroundColor' => 'rgba(210, 214, 222, 1)',
                'borderColor' => 'rgba(210, 214, 222, 1)',
                'pointRadius' => false,
                'pointColor' => 'rgba(210, 214, 222, 1)',
                'pointStrokeColor' => '#c1c7d1',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(220,220,220,1)',
                'data' => [65, 59, 80, 81, 56, 55, 40]
            ]
        ];

        $returnData['datasets'] = $dataSetObj;
        return json_encode($returnData);
    }

    private function lineChartData(){
        /* ## Sample Data for Line Chart
         * let data = [
            [0, 1, 2, 3, 4, 5, 6],
            [28, 48, 40, 19, 86, 27, 90],
            [65, 59, 80, 81, 56, 55, 40]
        ];*/

        return [
            [0, 1, 2, 3, 4, 5, 6, 7],
            [28, 48, 40, 19, 86, 27, 90,45],
            [65, 59, 80, 81, 56, 55, 40]
        ];
    }
}
