<?php

namespace App\Core\User\Http\Controllers;

use App\Core\User\Requests\UpdatePasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class UpdatePasswordController extends Controller
{
    public function __invoke(UpdatePasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            $request->user()->update([
                'password' => Hash::make($validated['confirm_password']),
                'updated_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Password changed successfully')->with('active_tab', 'pass_change');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Password change failed')->with('active_tab', 'pass_change');
        }

    }
}
