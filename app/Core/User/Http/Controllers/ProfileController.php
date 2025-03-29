<?php

namespace App\Core\User\Http\Controllers;

use App\Core\User\Models\User;
use App\Core\User\Requests\UpdateProfileRequest;
use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;


class ProfileController extends Controller
{
    public function index()
    {
        return view('User::profile.edit');
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        try {
            $decodeId = Encryption::decodeId($id);
            $user = User::whereId($decodeId)->first();
            $data = $request->validated();
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->national_id = $data['national_id'];
            $user->birth_date = $data['birth_date'];
            $user->present_address = $data['present_address'];
            $user->permanent_address = $data['permanent_address'];
            $user->gender = $data['gender'];
            $user->mobile = $data['mobile'];
            if ($request->user_pic_base64 !== null) {
                $user->photo = (new CommonFunction())->base64ImageStoreAndResize($request->user_pic_base64, 'uploads/profile', 100, 100, auth()->user()->first_name);
            }
            $user->save();

            return redirect()->back()->with('success', 'Profile updated successfully')->with('active_tab', 'details');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Profile updated failed')->with('active_tab', 'details');
        }
    }

}
