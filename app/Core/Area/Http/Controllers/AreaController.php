<?php

namespace App\Core\Area\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Area\Models\Area;
use App\Libraries\Encryption;
use AWS\CRT\HTTP\Request;
use Intervention\Image\Commands\ResponseCommand;
use App\Core\Area\Requests\StoreAreaRequest;
use App\Core\Area\Requests\UpdateAreaRequest;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{

    /**
     * Display the Area blade file screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("Area::area.list");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        $divisions = Area::where('area_type', 1)->get();
        return view('Area::area.create', [
            'divisions' => $divisions,
        ]);
    }

    /**
     * Store area data.
     * @param StoreAreaRequest area request data
     * @return redirect
     */
    public function store(StoreAreaRequest $request)
    {
//        dd($request->all());
        try {
            $areaData = $request->validated();
            $area = new Area();
            $area->area_nm = $areaData['area_nm'];
            $area->area_nm_ban = $areaData['area_nm_ban'];
            $area->area_type = $areaData['area_type'];
            if (isset($areaData['pare_id'])) {
                $area->pare_id = $areaData['pare_id'];
            } else {
                $area->pare_id = 0;
            }
            $area->save();
            return redirect()->route('area.index')->with('success', 'Area created.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('area.index')->with('error', 'Area action failed-[U-01]');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return view
     */
    public function edit($id)
    {
        $decodedId = Encryption::decode($id);
        $areaInfo = Area::find($decodedId);

        if($areaInfo->area_type == 3) {
            $districts = Area::where('id', $areaInfo->pare_id)->get(['id', 'pare_id', 'area_nm']);
            $divisionIds = $districts->pluck('pare_id')->toArray();
            $divisions = Area::whereIn('id', $divisionIds)->where('area_type', 1)->get();
        }
        elseif ($areaInfo->area_type == 2){
            $districts = Area::where('area_type', 2)->get();
            $divisions = Area::where('id', $areaInfo->pare_id)->get(['id', 'pare_id', 'area_nm']);
        }
        elseif ($areaInfo->area_type == 1) {

            $districts = Area::where('area_type', 2)->get();
            $divisions = Area::where('area_type', 1)->get();

        }
        return view('Area::area.edit', [
            'divisions' => $divisions,
            'id' => $id,
            'areaInfo' => $areaInfo,
            'districts' => $districts
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAreaRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateAreaRequest $request)
    {
        try {
            $id = $request->id;
            $decodedId = Encryption::decode($id);
            $area = Area::findOrFail($decodedId);
            $areaData = $request->validated();
            $area->fill($areaData);
            $area->save();
            return redirect()->route('area.index')->with('success', 'Area updated.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('area.index')->with('error', 'Area action failed.');
        }
    }

    /**
     * Show the district under the division.
     * @param $divisionId
     * @return Responce
     */

    public function getDistricts($divisionId)
    {
        $districts = Area::where('area_type', 2)->where('pare_id', $divisionId)->get(['id', 'area_nm']);
        return response()->json($districts);
    }

    /**
     * Area List Datatable.
     * @return DataTables
     */

    public function list()
    {
        $data = Area::select(['id', 'area_nm', 'area_nm_ban', 'area_type'])->get();
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return
                    '<a href="' . route('area.edit', Encryption::encode($row->id)) . '" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('area.destroy', Encryption::encode($row->id)) . '" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $decodedId = Encryption::decode($id);
            Area::find($decodedId)->delete();

            return redirect()->route('area.index')->with('success', 'Area Data Deleted.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('area.index')->with('error', 'Area Delete action failed [R-03]');
        }
    }
}
