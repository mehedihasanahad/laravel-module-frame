<?php

namespace App\Core\ProcessEngine\Http\Controllers;

use App\Core\ProcessEngine\Models\ProcessType;
use App\Core\ProcessEngine\Models\ProcessUserDesk;
use App\Core\ProcessEngine\Requests\StoreProcessUserDeskRequest;
use App\Core\ProcessEngine\Requests\UpdateProcessUserDeskRequest;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class ProcessUserDeskController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:process-user-desk-view|process-user-desk-create|process-user-desk-update|process-user-desk-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:process-user-desk-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:process-user-desk-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:process-user-desk-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ProcessEngine::user-desk.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('ProcessEngine::user-desk.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessUserDeskRequest $request
     * @param ProcessUserDesk $process_user_desk
     * @return RedirectResponse
     */
    public function store(StoreProcessUserDeskRequest $request, ProcessUserDesk $process_user_desk)
    {
        try {
            $process_user_desk->fill($request->validated())->save();
            return redirect()->route('process-user-desk.index')->with('success', 'Process user desk  created successfully');
        } catch (Exception $e) {
            return redirect()->route('process-user-desk.create')->with('error', 'Process user desk action failed-[P-01]');
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
    public function edit($id)
    {
        $decodedId = Encryption::decodeId($id);
        $process_user_desk = ProcessUserDesk::find($decodedId);
        return view('ProcessEngine::user-desk.edit', compact('process_user_desk'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProcessUserDeskRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateProcessUserDeskRequest $request, $id)
    {
        try {
            $decodedId = Encryption::decodeId($id);
            $process_user_desk = ProcessUserDesk::find($decodedId);
            $process_user_desk->fill($request->validated())->update();
            return redirect()->route('process-user-desk.index')->with('success', 'Process user desk updated successfully');
        } catch (Exception $e) {
            return redirect()->route('process-user-desk.create')->with('error', 'Process user desk action failed-[P-01]');
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
        $decodedId = Encryption::decodeId($id);
        $process_user_desk = ProcessUserDesk::find($decodedId);
        $process_user_desk->delete();
        return redirect()->route('process-user-desk.index')->with('success', 'Process user desk deleted successfully');
    }

    /**
     * Show the list.
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function list()
    {
        $processTypes = ProcessUserDesk::orderByDesc('id')->get();
        return DataTables::of($processTypes)
            ->addColumn('action', function ($row) {
                return
                    '<a href="' . route('process-user-desk.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('process-user-desk.destroy', Encryption::encodeId($row->id)) . '" method="POST">
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
}
