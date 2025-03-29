<?php

namespace App\Core\Document\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Document\Models\Documents;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Core\Document\Models\DocListForService;
use App\Core\Document\Models\ProcessType;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class DocumentControllers extends Controller
{
    public function index()
    {
        return view('Document::document');
       
    }

    public function add(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'max_size' => 'nullable|numeric',
                'min_size' => 'nullable|numeric',
                'status' => 'required|in:0,1',
            ]);
            $documentData = new Documents();
            $documentData->name = $validatedData['name'];
            $documentData->max_size = $validatedData['max_size'];
            $documentData->min_size = $validatedData['min_size'];
            $documentData->status = $validatedData['status'];
            $documentData->updated_by = Auth::user()->id;
            $documentData->created_by = Auth::user()->id;

            if ($request->hasFile('simple_file')) {
                $file = $request->file('simple_file');
                $fileSize = $file->getSize();
                $maxSizeKB = $validatedData['max_size'] * 1024;
                $minSizeKB = $validatedData['min_size'] * 1024;

                if ($fileSize < $minSizeKB || $fileSize > $maxSizeKB) {
                    Session::flash('error', 'File size should be between ' . $minSizeKB . ' KB and ' . $maxSizeKB . ' KB');
                    return redirect()->back()->withInput();
                }

                $destinationPath = public_path('public'); // Set the desired destination path
                $fileName = $file->getClientOriginalName(); // Get the original file name
                $file->move($destinationPath, $fileName); // Move the file to the destination

                $documentData->simple_file = 'public/' . $fileName; // Set the file path in the database
            }

            $documentData->save();
            Session::flash('success', 'Document created successfully');
            return redirect('/document');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            foreach ($errors->all() as $error) {
                Session::flash('error', $error);
            }
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    public function addProcess(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'doc_name' => 'required',
                'process_name' => 'required',
                'others' => 'nullable|numeric',
                'is_required' => 'required|in:0,1',
                'autosuggest_status' => 'nullable|in:0,1',
                'Status' => 'required|in:0,1',
            ]);

            $docListForService = new DocListForService();
            $docListForService->doc_id = $validatedData['doc_name'];
            $docListForService->process_type_id = $validatedData['process_name'];
            $docListForService->order = $validatedData['others'];
            $docListForService->is_required = $validatedData['is_required'];
            $docListForService->autosuggest_status = $validatedData['autosuggest_status'] ?? 0;
            $docListForService->status = $validatedData['Status'];
            $docListForService->is_archive = 0;
            $docListForService->created_by = (Auth::user()->id);
            $docListForService->updated_by = (Auth::user()->id);
            $docListForService->save();

            Session::flash('success', 'Process Document add successfully');
            return redirect('/document');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }


    public function updateDocumentDetails(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required',
                'name' => 'required',
                'status' => 'required|in:0,1',
                'max_size' => 'nullable|numeric',
                'min_size' => 'nullable|numeric',

            ]);

            $decodedId = Encryption::decode($validatedData['id']);
            $documentData = Documents::find($decodedId);

            $documentData->name = $validatedData['name'];
            $documentData->max_size = $validatedData['max_size'];
            $documentData->min_size = $validatedData['min_size'];

            if ($request->hasFile('simple_file')) {
                $file = $request->file('simple_file');
                $fileSize = $file->getSize();
                $maxSizeKB = $validatedData['max_size'] * 1024;
                $minSizeKB = $validatedData['min_size'] * 1024;
                if ($fileSize < $minSizeKB || $fileSize > $maxSizeKB) {
                    Session::flash('error', 'File size should be between ' . $minSizeKB . ' KB and ' . $maxSizeKB . ' KB');
                    return redirect()->back()->withInput();
                }
                $path = $file->store('public/images');
                $documentData->simple_file = $path;
            }
            $documentData->status = $validatedData['status'];
            $documentData->save();

            Session::flash('success', 'Document updated successfully');
            return redirect('/document');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            foreach ($errors->all() as $error) {
                Session::flash('error', $error);
            }
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    public function updateProcessDocumentDetails(Request $request)
    {
        try {

            $f = $request->id;


            $decodedId = Encryption::decode($f);

            $documentData  = DocListForService::find($decodedId);

            if ($documentData) {
                $documentData->doc_id = $request->doc_name;
                $documentData->process_type_id = $request->process_name;
                $documentData->order = $request->order;
                $documentData->is_required = $request->is_required;
                $documentData->autosuggest_status = $request->autosuggest_status;
                $documentData->status = $request->status;
                $documentData->is_archive = 0;
                $documentData->created_by = (Auth::user()->id);
                $documentData->updated_by = (Auth::user()->id);
                // dd($documentData);
                $documentData->save();
                Session::flash('success', 'Document updated successfully');
                return redirect('/document');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            foreach ($errors->all() as $error) {
                Session::flash('error', $error);
            }
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function fetchDocumentData()
    {
        $docNames = DB::table('doc_name')->where('status', 1)->pluck('name', 'id');
        $processTypes = DB::table('process_types')->where('status', 1)->pluck('name', 'id');
        return response()->json(['docNames' => $docNames, 'processTypes' => $processTypes]);
    }

    public function editDocument($id)
    {

        try {
            $decodedId = Encryption::decode($id);
            $documentData = Documents::find($decodedId);

            if ($documentData) {
                return view('Document::document-edit', [
                    'id' => $id,
                    'name' => $documentData->name,
                    'max_size' => $documentData->max_size,
                    'min_size' => $documentData->min_size,
                    'simple_file' => $documentData->simple_file,
                    'status' => $documentData->status,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function editProcess($id)
    {
        try {
            $decodedId = Encryption::decode($id);
            $documentData = DocListForService::find($decodedId);

            if ($documentData) {
                $docNames = DB::table('doc_name')->where('status', 1)->pluck('name', 'id');
                $processTypes = DB::table('process_types')->where('status', 1)->pluck('name', 'id');
                $doc_list_for_service = DB::table('doc_list_for_service')->where('id', $decodedId)->first();

                return view('Document::process-edit', [
                    'documentData' => $documentData,
                    'docNames' => $docNames,
                    'processTypes' => $processTypes,
                    'doc_list_for_service' => $doc_list_for_service,
                    'id'=>$id,
                  
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
