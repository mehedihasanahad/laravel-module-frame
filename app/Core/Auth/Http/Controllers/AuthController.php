<?php

namespace App\Core\Auth\Http\Controllers;

use App\Core\Auth\Http\Controllers\Traits\AuthHandlers;
use App\Core\Auth\Models\AreaInfo;
use App\Core\Auth\Requests\UpdatePasswordRequest;
use App\Core\User\Models\User;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Psy\Util\Json;
use App\Libraries\SendSMS;

class AuthController extends Controller
{
    use AuthHandlers;

    /**
     * View login page
     * @return view
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     * @param Request $request user request body.
     * @return RedirectResponse
     */
    public function login(Request $request)
    {
        //  dd($request->all());
        // dd(1);
        $request->validate([
            'email' => 'required',
            'password' => ['required', 'min:6']
        ], [
            'email.required' => 'Please provide your email',
            'password.required' => 'Please provide your password',
            'password.min' => 'Password should contain minimum 6 Characters',
        ]);
        // dd($request->all());
        // dd(1);
        if (!$this->checkBruteForceAttack($request)) {
            return back()->withInput();
        }
        // dd($request->all());
        // dd(1);
        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'status' => 1
        ])) {
            Auth::logoutOtherDevices($request->get('password'));
            if (Auth::user()->is_approved != 1){
                return redirect('profile')->with('success', trans('Auth::messages.welcome'));
            }
            else{
                return redirect('/dashboard')->with('success', trans('Auth::messages.welcome'));
            }
        }
        // dd($request->all());
        $this->_failedLogin($request);
        return back()->withInput()->with('error', 'Invalid Credentials [L07]');
    }

    /**
     * View registration page
     * @return View
     */
    public function registration()
    {
        $divisions = AreaInfo::query()
            ->where('area_type', AreaInfo::AreaType['Division'])
            ->get(['id', 'area_nm']);
        return view('auth.registration', compact('divisions'));
    }

    /**
     * Logout current authenticated user
     * @return View
     */
    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect('/login');
    }

    /**
     * View forget password page
     * @return View
     */
    public function forgetPassword()
    {
        return view('auth.forget-password');
    }

    /**
     * Verify Email
     * @param Request $request user request body
     * @return Json
     */
    public function verifyEmail(Request $request)
    {
        try {
            $email = $request['email'];
            $user = User::where('email', $email)->first(['mobile', 'otp', 'otp_expire_time']);
            if (!$user) {
                $msg = 'User not found';
                return response()->json(['responseCode' => -1, 'msg' => $msg]);
            }
            if (!$user->mobile) {
                $msg = 'User mobile is not updated. Please contact with the system admin.';
                return response()->json(['responseCode' => -1, 'msg' => $msg]);
            }

            $mobile_no_length = strlen($user->mobile);
            $mobile_no_last_11_digit = substr($user->mobile, -11);
            $masked_destination = 'AuthController' . substr($user->mobile, 0, $mobile_no_length - 11) . '****' . substr($user->mobile, -2);


            if ($user['otp'] && (strtotime($user['otp_expire_time']) > time())) {
                $msg = "OTP has already been send to $masked_destination.";
                return response()->json(['responseCode' => 1, 'msg' => $msg]);
            }

            $otp = rand(99999, 1000000);
            $message = "Your OTP is: $otp. \n" . env('APP_NAME');

            $res = SendSMS::sendFastSMS($user->mobile, $message);
            if ($res['status'] == 200) {
                User::where('email', $email)->update([
                    'otp' => $otp,
                    'otp_expire_time' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                ]);
            }
            $msg = "OTP has been send to $masked_destination";

            return response()->json(['responseCode' => 1, 'msg' => $msg]);
        } catch (\Exception $e) {
            #dd($e->getMessage(), $e->getFile(), $e->getLine());
            $msg = 'Error Occurred';
            return response()->json(['responseCode' => -1, 'msg' => $msg]);
        }
    }

    /**
     * Verify OTP
     * @param Request $request user request body
     * @return Json
     */
    public function verifyOTP(Request $request)
    {
        try {
            $email = $request['email'];
            $otp = $request['otp'];
            $user = User::where('email', $email)->first(['mobile', 'otp', 'otp_expire_time']);
            if (!$user) {
                $msg = 'User not found';
                return response()->json(['responseCode' => -1, 'msg' => $msg]);
            }

            if ($user['otp'] == $otp && (strtotime($user['otp_expire_time']) > time())) {
                $msg = "OTP matched";
                return response()->json(['responseCode' => 1, 'msg' => $msg]);
            } else {
                $msg = "OTP not matched";
                return response()->json(['responseCode' => -1, 'msg' => $msg]);
            }
        } catch (\Exception $e) {
            $msg = 'Error Occurred';
            return response()->json(['responseCode' => -1, 'msg' => $msg]);
        }
    }

    /**
     * Change password
     * @param Request $request user request body
     * @return Json
     */
    public function changePass(Request $request)
    {
        try {
            $email = $request['email'];
            $password = $request['password'];
            $user = User::where('email', $email)->first();
            if (!$user) {
                $msg = 'User not found';
                return response()->json(['responseCode' => -1, 'msg' => $msg]);
            }
            User::where('email', $email)->update([
                'password' => Hash::make($password),
                'otp' => null,
                'otp_expire_time' => null,
            ]);
            $msg = "Password changed successfully";
            Session::flash('message', $msg);
            return response()->json(['responseCode' => 1, 'msg' => $msg]);
        } catch (\Exception $e) {
            $msg = 'Password cannot be changed';
            Session::flash('error', $msg);
            return response()->json(['responseCode' => -1, 'msg' => $msg]);
        }
    }


    /**
     * Password Set Up Page
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function setPassword()
    {
        return view('auth.set-password');
    }


    /**
     * Updating user password from null to user provided password
     * @param UpdatePasswordRequest $request
     * @param $userId
     * @return RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request, $userId)
    {
        try {
            $data = $request->validated();
            $decodedId = Encryption::decodeId($userId);
            $user = User::where('id', $decodedId)->first();
            $user->password = Hash::make($data['password']);
            $user->update();
            return redirect()->route('login');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', 'Error Occurred');
        }

    }
}
