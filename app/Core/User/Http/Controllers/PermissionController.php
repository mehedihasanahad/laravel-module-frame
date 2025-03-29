<?php

namespace App\Core\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission-view|permission-create|permission-update|permission-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('User::permission.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('User::permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required'
        ]);
        try {
            Permission::create(['name' => $request->name, 'guard_name' => $request->guard_name]);

            return redirect()->route('permissions.index')->with('success', 'Permission created');
        } catch (\Exception $e) {
            return redirect()->route('permissions.index')->with('error', 'Permission action failed-[P-01]');
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $decodedId = Encryption::decode($id);
        $permission = Permission::where('id', $decodedId)->first();
        return view('User::permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required'
        ]);
        try {
            $decodedId = Encryption::decode($id);

            Permission::find($decodedId)
                ->update(['name' => $request->name, 'guard_name' => $request->guard_name]);

            return redirect()->route('permissions.index')->with('success', 'Permission updated');
        } catch (\Exception $e) {
            return redirect()->route('permissions.index')->with('error', 'Permission action failed-[P-02]');
        }
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
            Permission::find($decodedId)->delete();

            return redirect()->route('permissions.index')->with('success', 'Permission Deleted');
        } catch (\Exception $e) {
            return redirect()->route('permissions.index')->with('error', 'Permission action failed-[P-03]');
        }
    }

    /**
     * Show the list.
     * @return Yajra\DataTables\Facades\DataTables
     */
    public function list() {
        $permissions = Permission::orderByDesc('id')->get();

        return DataTables::of($permissions)
            ->addColumn('action', function ($row) {
                return '
                <a href="' . route('permissions.edit', Encryption::encode($row->id)) . '" class="btn btn-sm btn-secondary">
                    <i class="fas fa-edit"></i>
                </a>'
//                <form style="display: inline-block" action="' . route('permissions.destroy', Encryption::encode($row->id)) . '" method="POST">
//                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
//                    <input type="hidden" name="_method" value="DELETE"/>
//                    <button class="btn btn-sm btn-danger">
//                        <i class="fas fa-trash"></i>
//                    </button>
//                </form>'
               ;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }
}
