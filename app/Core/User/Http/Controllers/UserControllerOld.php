<?php

namespace App\Core\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Core\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserControllerOld extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return view('User::index', ['users' => $users]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function addUser()
    {
        $roles = Role::pluck('name','name')->all();
        return view('User::add-form', compact('roles'));
    }

    public function add(Request $request)
    {
        $userData = $request->all();
//        $userData['user_group_id'] = 6;
        $userData['user_group_id'] = implode(',', [...Role::whereIn('name', $request->roles)->pluck('id', 'id')]);
        $userData['password'] = Hash::make($request['password']);
        $user = User::create($userData);
        $user->assignRole($request->input('roles'));
        Session::flash('success', 'User created successfully');
        return redirect('/users');
    }

    public function twoStep()
    {
        dd('twoStep');
    }
    public function profileinfo()
    {
        return view('User::profile_info');
    }

    public function profileUpdate(Request $request)
    {
        try {
            $requestedData = $request->all();
            $user = User::find(Auth::user()->id);
            if (!$user) {
                Session::flash('error', 'Invalid user!');
                return redirect('/dashboard');
            }

            if ($request->hasFile('photo')) {
                $avatarName = time() . 'Controllers' .$request->file('photo')->getClientOriginalExtension();
                $directoryPath = public_path('assets/images/users');

                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath);
                }

                $filePath = 'assets/images/users/' . $avatarName;
                $image = Image::make($request->file('photo')->path());

                // Resize the image while maintaining its aspect ratio

                $image->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($directoryPath.'/'.$avatarName);

                $currentUserPhoto = $user->photo;
                if (!empty($currentUserPhoto)) {
                    $currentPhotoPath = public_path($currentUserPhoto);
                    if (File::exists($currentPhotoPath)) {
                        File::delete($currentPhotoPath);
                    }
                }
                $requestedData['photo'] = $filePath;
            }

            if (!empty($requestedData['photo'])){
                Auth()->user()->update(['photo' => $requestedData['photo']]);
            }

            $user->update($requestedData);

            Session::flash('success', "User Data updated successfully.");
            return redirect('user/profileinfo');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
            return redirect('/');
        }
    }


    public function getUserDetails($userId)
    {
        try {
            $user = User::find(Crypt::decryptString($userId));
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function editUserDetails($edituserId)
    {

        try {
            $user = User::find(Crypt::decryptString($edituserId));
            if ($user) {
                return response()->json($user);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function updateUserDetails(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'userId' => 'required',
                'status' => 'required|in:0,1',
            ]);

            $user = User::find(Crypt::decryptString($validatedData['userId']));
            // Update the user details
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->national_id = $request->input('national_id');
            $user->birth_date = $request->input('birth_date');
            $user->status = $request->input('status');

            $user->save();
            Session::flash('success', 'User Update successfully');
            return redirect('/users');
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function deleteUserDetails($userId)
    {
        try {
            $user = User::find(Crypt::decryptString($userId));

            if ($user) {

                $user->delete();
                Session::flash('success', 'User delete successfully');
                return redirect('/users');
            } else {

                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function updatePass(Request $request)
    {
        try {
            $password = $request['password'];
            User::where([
                'id' => Auth::user()->id,
            ])->update([
                'password' => Hash::make($password),
                'otp' => null,
                'otp_expire_time' => null,
            ]);
            $msg = "Password changed successfully";
            return response()->json(['responseCode' => 1, 'msg' => $msg]);
        } catch (\Exception $e) {
            $msg = 'Password cannot be changed';
            return response()->json(['responseCode' => -1, 'msg' => $msg]);
        }
    }
}
