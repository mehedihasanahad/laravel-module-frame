<?php

namespace App\Core\Document\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Core\Document\Models\Documents;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{
    public function documentlist(Request $request)
    {
        
        if ($request->ajax()) {
            $data = Documents::select(['id', 'name', 'simple_file', 'status'])->get();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('edit-document', \App\Libraries\Encryption::encode($row->id)) . ' " class="editModal" ><span class="fas fa-edit m-1"></span>Edit</a>';
                })
                ->rawColumns(['action']) // Specify the 'action' column as a raw HTML column
                ->toJson();
                
        }
        
        return view('/document');
    }

    public function processlist(Request $request)
    {
        
        if ($request->ajax()) {
            $data = Documents::join('doc_list_for_service', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
            ->join('process_types', 'doc_list_for_service.process_type_id', '=', 'process_types.id')
            ->select('doc_list_for_service.id AS id', 'doc_name.name', 'doc_list_for_service.is_required', 'doc_list_for_service.status AS doc_list_status', 'process_types.name AS process_type_name')
            ->get();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return ' <a href="' . route('edit-process', \App\Libraries\Encryption::encode($row->id)) . ' " class="open-process-edit-modal" ><span class="fas fa-edit m-1"></span>Edit</a>';
                })
                ->rawColumns(['action'])
                ->toJson();
                
        }
        
        return view('/document');
    }
}
