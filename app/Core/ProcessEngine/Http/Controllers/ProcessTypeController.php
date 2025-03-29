<?php

namespace App\Core\ProcessEngine\Http\Controllers;

use App\Core\ProcessEngine\Models\ProcessType;
use App\Core\ProcessEngine\Requests\StoreProcessTypeRequest;
use App\Core\ProcessEngine\Requests\UpdateProcessTypeRequest;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class ProcessTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:process-type-view|process-type-create|process-type-update|process-type-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:process-type-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:process-type-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:process-type-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the process.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ProcessEngine::process-type.list');
    }

    /**
     * Show the form for creating a new process.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $permissions = Permission::get(['name']);
        return view('ProcessEngine::process-type.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessTypeRequest $request
     * @param ProcessType $process_type
     * @return RedirectResponse
     */
    public function store(StoreProcessTypeRequest $request, ProcessType $process_type)
    {
        try {
            $process_type->active_for_permissions = implode('|', $request->get('permissions', null));
            $process_type->fill($request->validated())->save();
            return redirect()->route('process-type.index')->with('success', 'Process type created successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->route('process-type.create')->with('error', 'Process type create action failed-[P-01]');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $decodedId = Encryption::decodeId($id);
        $process_type = ProcessType::find($decodedId);
        $permissions = Permission::get(['name']);
        $permittedPermissions = explode('|', $process_type->active_for_permissions);
        return view('ProcessEngine::process-type.edit', compact('process_type', 'permissions','permittedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProcessTypeRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateProcessTypeRequest $request, $id)
    {
        try {
            $decodeId = Encryption::decodeId($id);
            $processType = ProcessType::find($decodeId);
            $processType->active_for_permissions = implode('|', $request->get('permissions', null));
            $processType->fill($request->validated())->update();
            return redirect()->route('process-type.index')->with('success', 'Process type updated successfully');
        } catch (Exception $e) {
            return redirect()->route('process-type.create')->with('error', 'Process action failed-[P-01]');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $decodeId = Encryption::decodeId($id);
        $processType = ProcessType::find($decodeId);
        $processType->delete();
        return redirect()->route('process-type.index')->with('success', 'Process type deleted successfully');
    }

    /**
     * Show the list.
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function list()
    {
        $processTypes = ProcessType::orderByDesc('id')->get();
        return DataTables::of($processTypes)
            ->addColumn('action', function ($row) {
                return
                    '
                    <a href="' . route('process-type.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('process-type.destroy', Encryption::encodeId($row->id)) . '" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    ';
            })
            ->addColumn('statuses', function ($row) {
                return
                    '<a href="' . route('process-statuses.index', ['process_type_id' => Encryption::encodeId($row->id)]) . ' " class="btn btn-sm btn-secondary">
                       <i class="fas fa-eye"></i>
                    </a>
                    ';
            })
            ->rawColumns(['action', 'statuses'])
            ->addIndexColumn()
            ->make(true);
    }
}
