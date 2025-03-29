<?php

namespace App\Core\User\Http\Controllers;

use App\Core\ProcessEngine\Models\ProcessUserDesk;
use App\Core\User\Models\User;
use App\Core\User\Requests\StoreUserRequest;
use App\Core\User\Requests\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-view|user-create|user-update|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('User::user.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $userDesks = ProcessUserDesk::orderByDesc('id')->get();
        return view('User::user.create', compact('roles', 'userDesks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $userData = $request->validated();
            $userData['user_group_id'] = Role::where('name', $request->roles)->first(['id']);
            $userData['password'] = Hash::make($request['password']);
            $userData['desk_id'] = implode(',', $request->get('desk_id', [0]));
            $user = User::create($userData);
            $user->assignRole($request->input('roles'));
            return redirect()->route('users.index')->with('success', 'User created.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'User action failed-[U-01]');
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
        $roles = Role::orderByDesc('id')->get();
        $userDesks = ProcessUserDesk::orderByDesc('id')->get();
        $decodedId = Encryption::decode($id);
        $user = User::with(['roles'])->find($decodedId);
        $role_id = $user->roles->pluck('id')->first();
        $desks_id = explode(',', $user->desk_id);
        return view('User::user.edit', compact('user', 'roles', 'role_id', 'userDesks', 'desks_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find(Encryption::decode($id));
            // Update the user details
            $user->desk_id = implode(',', $request->get('desk_id', [0]));
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->national_id = $request->input('national_id');
            $user->birth_date = $request->input('birth_date');
            $user->status = $request->input('status');
            $user->user_group_id = $request->input('role_id');
            $user->save();

//            $role = Role::find($request->input('role_id'));
//            $currentRole = ($user->roles[0])->name;
//            $hasRole = $user->hasRole($role->name);
//            if (!$hasRole)  {
//                $user->assignRole($request->input('role_id'));
//                $user->removeRole($currentRole);
//            }

            $user->syncRoles([]);
            $user->assignRole($request->input('role_id'));

            return redirect()->route('users.index')->with('success', 'User Updated.');
        } catch (\Exception $e) {
//            dd($e->getMessage());
            return redirect()->route('users.index')->with('error', 'User action failed-[U-01]');
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
        try {
            $decodedId = Encryption::decode($id);
            User::find($decodedId)->delete();
            return redirect()->route('users.index')->with('success', 'User Deleted.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'User action failed [R-03]');
        }
    }

    /**
     * User List Datatable.
     * @return DataTables
     */
    public function list()
    {
        $users = User::with('roles:id,name')->orderByDesc('id')->get();
        return DataTables::of($users)
            ->addColumn('action', function ($row) {
                return
                    '<a href="' . route('users.edit', Encryption::encode($row->id)) . '" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('users.destroy', Encryption::encode($row->id)) . '" method="POST">
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
