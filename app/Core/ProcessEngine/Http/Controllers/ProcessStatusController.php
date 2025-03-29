<?php

namespace App\Core\ProcessEngine\Http\Controllers;

use App\Core\ProcessEngine\Models\ProcessStatus;
use App\Core\ProcessEngine\Models\ProcessType;
use App\Core\ProcessEngine\Models\Status;
use App\Core\ProcessEngine\Requests\StoreProcessStatusRequest;
use App\Core\ProcessEngine\Requests\UpdateProcessStatusRequest;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class ProcessStatusController extends Controller
{

    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:process-status-view|process-status-create|process-status-update|process-status-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:process-status-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:process-status-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:process-status-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ProcessEngine::process-status.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $statuses = Status::get(['id', 'name']);
        $processTypes = ProcessType::query()->active()->get();
        return view('ProcessEngine::process-status.create', compact('processTypes', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessStatusRequest $request
     * @param ProcessStatus $process_status
     * @return RedirectResponse
     */
    public function store(StoreProcessStatusRequest $request, ProcessStatus $process_status)
    {
        try {
            $data = $request->validated();
            //check if that process status already exists in the given process type
            $processExists = $this->checkProcessExists($data['id'], $data['process_type_id']);

            if (!empty($processExists)) {
                return redirect()->route('process-statuses.index')->with('warning', 'Process status already exists in the given process type');
            }

            $process_status->fill($data)->save();
            return redirect()->route('process-statuses.index')->with('success', 'Process status created successfully');
        } catch (Exception $e) {
//            return redirect()->route('process-statuses.create')->with('error', $e->getMessage());
            return redirect()->route('process-statuses.create')->with('error', 'Process status action failed-[PS-01]');
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
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(Request $request, $id)
    {
        $decodeId = Encryption::decodeId($id);
        $decodeProcessTypeId = Encryption::decodeId($request->get('process_type_id'));
        $statuses = Status::get(['id', 'name']);
        $processTypes = ProcessType::query()->active()->get();
        $process_status = ProcessStatus::whereId($decodeId)->whereProcessTypeId($decodeProcessTypeId)->first();
        return view('ProcessEngine::process-status.edit', compact('processTypes', 'process_status', 'statuses'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProcessStatusRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateProcessStatusRequest $request, $id)
    {
        try {
            $decodeId = Encryption::decodeId($id);
            $decodeProcessTypeId = Encryption::decodeId($request->query('process_type_id'));
            $data = $request->validated();

            //check if that process status already exists in the given process type
            $processExists = $this->checkProcessExists($data['id'], $data['process_type_id']);

            if ((($data['process_type_id'] != $decodeProcessTypeId)
                    || ($data['id'] != $decodeId))
                && !empty($processExists)) {
                return redirect()->route('process-statuses.index')->with('warning', 'Process status already exists in the given process type');
            }

            $process_status = ProcessStatus::whereId($decodeId)->whereProcessTypeId($decodeProcessTypeId)->first();
            $process_status->fill($data)->save();
            return redirect()->route('process-statuses.index')->with('success', 'Process status updated successfully');
        } catch (Exception $e) {
//            return redirect()->back()->with('error', $e->getMessage());
            return redirect()->back()->with('error', 'Process status action failed-[PS-01]');
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
        $process_status = ProcessStatus::find($decodeId);
        $process_status->delete();
        return redirect()->route('process-statuses.index')->with('success', 'Process type deleted successfully');
    }


    /**
     * Show the list.
     * @return JsonResponse
     * @throws Exception
     */
    public function list(Request $request)
    {
        $processTypes = ProcessStatus::query()
            ->when($request->get('process_type_id'), function ($query) use ($request) {
                return $query->processType(Encryption::decodeId($request->get('process_type_id')));
            })
            ->orderByDesc('id')
            ->with('processType:id,name')
            ->get(['id', 'process_type_id', 'status_name', 'status']);
        return DataTables::of($processTypes)
            ->addColumn('action', function ($row) {
                return
                    '<a href="' . route('process-statuses.edit', ['process_status' => Encryption::encodeId($row->id), 'process_type_id' => Encryption::encodeId($row->process_type_id)]) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('process-statuses.destroy', Encryption::encodeId($row->id)) . '" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    ';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    protected function checkProcessExists($id, $process_type_id)
    {
        return ProcessStatus::whereId($id)->whereProcessTypeId($process_type_id)->first();
    }
}
