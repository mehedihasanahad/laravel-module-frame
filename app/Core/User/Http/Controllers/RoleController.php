<?php

namespace App\Core\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-view|role-create|role-update|role-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('User::role.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('User::role.create', compact('permissions'));
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
            'guard_name' => 'required',
//            'permissions' => 'required'
        ]);
        try {
            $role = new Role();
            $role->name = $request->name;
            $role->guard_name = $request->guard_name;
            $role->save();
            $role->syncPermissions($request->input('permissions'));

            return redirect()->route('roles.index')->with('success', 'Role created.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Role action failed-[R-01]');
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
        $role = Role::find($decodedId);
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $decodedId)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('User::role.edit', compact('permissions', 'rolePermissions', 'role'));

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
            'guard_name' => 'required',
//            'permissions' => 'required',
        ]);
        try {
            $decodedId = Encryption::decode($id);

            $role = Role::find($decodedId);
            $role->name = $request->name;
            $role->guard_name = $request->guard_name;
            $role->save();
            $role->syncPermissions($request->input('permissions'));

            return redirect()->route('roles.index')->with('success', 'Role Updated.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Role action failed-[R-02]');
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
            Role::find($decodedId)->delete();

            return redirect()->route('roles.index')->with('success', 'Role Deleted.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Role action failed [R-03]');
        }
    }

    /**
     * Show the list.
     * @return Yajra\DataTables\Facades\DataTables
     */
    public function list() {
        $roles = Role::orderByDesc('id')->get();
        return DataTables::of($roles)
            ->addColumn('action', function ($row) {
                return
                    '<a href="' . route('roles.edit', Encryption::encode($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('roles.destroy', Encryption::encode($row->id)) . '" method="POST">
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
}
