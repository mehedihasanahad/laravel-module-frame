<?php

namespace App\Core\ProcessEngine\Http\Controllers;


use App\Core\ProcessEngine\Models\ProcessPath;
use App\Core\ProcessEngine\Models\ProcessType;
use App\Core\ProcessEngine\Models\ProcessUserDesk;
use App\Core\ProcessEngine\Requests\StoreProcessPathRequest;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProcessPathController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:process-path-view|process-path-create|process-path-update|process-path-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:process-path-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:process-path-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:process-path-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ProcessEngine::process-path.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $process_types = ProcessType::query()->active()->get(['id', 'name']);
        $user_desks = ProcessUserDesk::query()->active()->get(['id', 'name']);
        return view('ProcessEngine::process-path.create', compact('process_types', 'user_desks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessPathRequest $request
     * @param ProcessPath $process_path
     * @return RedirectResponse
     */
    public function store(StoreProcessPathRequest $request, ProcessPath $process_path)
    {
        try {
            $process_path->fill($request->validated())->save();
            return redirect()->route('process-path.index')->with('success', 'Process path created successfully');
        } catch (Exception $e) {
//            return redirect()->route('process-path.create')->with('error', $e->getMessage());
            return redirect()->route('process-path.create')->with('error', 'Process user desk action failed-[P-01]');
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
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $decodeId = Encryption::decodeId($id);
        $process_path = ProcessPath::find($decodeId);
        $process_types = ProcessType::query()->active()->get(['id', 'name']);
        $user_desks = ProcessUserDesk::query()->active()->get(['id', 'name']);
        return view('ProcessEngine::process-path.edit', compact('process_types', 'user_desks', 'process_path'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreProcessPathRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(StoreProcessPathRequest $request, $id)
    {
        try {
            $decodeId = Encryption::decodeId($id);
            $process_path = ProcessPath::find($decodeId);
            $process_path->fill($request->validated())->save();
            return redirect()->route('process-path.index')->with('success', 'Process path updated successfully');
        } catch (Exception $e) {
//            return redirect()->route('process-path.create')->with('error', $e->getMessage());
            return redirect()->route('process-path.create')->with('error', 'Process path update action failed-[P-01]');
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
        $processType = ProcessPath::find($decodeId)->delete();
        return redirect()->route('process-path.index')->with('success', 'Process path deleted successfully');
    }

    /**
     * Returns List of data
     * @return JsonResponse
     * @throws \Exception
     */
    public function list()
    {
        $processPaths = ProcessPath::query()
            ->with(['processType:id,name', 'deskFrom:id,name', 'deskTo:id,name', 'statusFrom:id,status_name', 'statusTo:id,status_name'])
            ->orderByDesc('id')
            ->get();


        return DataTables::of($processPaths)
            ->addColumn('action', function ($row) {
                return
                    '
                    <a href="' . route('process-path.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('process-path.destroy', Encryption::encodeId($row->id)) . '" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    ';
            })
            ->rawColumns(['action', 'statuses'])
            ->addIndexColumn()
            ->make(true);
    }
}
