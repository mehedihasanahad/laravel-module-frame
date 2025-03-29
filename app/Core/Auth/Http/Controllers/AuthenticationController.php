<?php

namespace App\Core\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\SendSMS;
use App\Core\Auth\Http\Controllers\Traits\AuthHandlers;
use App\Core\Auth\Requests\AuthenticationRequest;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{

    use AuthHandlers;

    public function index()
    {
        return view('auth.login');
    }

    public function login(AuthenticationRequest $request)
    {
        $validated = $request->validated();
        if (!$this->_checkAttack($request)) {
            return redirect()->back()->withInput();
        }


        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])
        ) {
            #$this->killUserSession(Auth::user()->id);
            if (Auth::user()->is_approved != 1) {
                Auth::logout();
                return redirect()->back()
                    ->withInput()
                    ->with('error', trans('Auth::messages.user_not_approved') . '[AC: 0001]');
            }

            if (Auth::user()->is_approved == 1 && Auth::user()->status != 1) {
                Auth::logout();
                return redirect()->back()
                    ->withInput()
                    ->with('error', trans('Auth::messages.user_not_active') . '[AC: 0002]');
            }

            $userTypeRootStatus = $this->_checkUserTypeRootActivation();
            if (!$userTypeRootStatus) {
                return redirect('/login');
            }

            /*
            if ($this->_setSession() == false) {
                Auth::logout();
                return redirect('/login');
            }
            */


            if (!$this->isValidForLogin()) {
                Session::put('is_two_step_verified', 'NO');
                return redirect()
                    ->intended('/users/two-step')
                    ->with('success', 'Logged in successfully, Please verify the 2nd steps.');
            }

            if (Auth::user()->is_approved != 1) {
                return redirect()
                    ->intended('/user/profileinfo')
                    ->with('success', trans('Auth::messages.welcome'));
            } else {
                return redirect()
                    ->intended('/dashboard')
                    ->with('success', trans('Auth::messages.welcome'));
            }
        }

        $this->_failedLogin($request);
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Invalid email or password [L07]');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect('/login');
    }

    public function forgetPassword()
    {
        return view('auth.forget-password');
    }

    public function verifyEmail(Request $request)
    {
        try {
            $email = $request['email'];
            $user = User::where('email',$email)->first(['mobile','otp','otp_expire_time']);
            if(!$user){
                $msg = 'User not found';
                return response()->json(['responseCode'=>-1,'msg'=>$msg]);
            }
            if(!$user->mobile){
                $msg = 'User mobile is not updated. Please contact with the system admin.';
                return response()->json(['responseCode'=>-1,'msg'=>$msg]);
            }

            $mobile_no_length        = strlen( $user->mobile );
            $mobile_no_last_11_digit = substr( $user->mobile, - 11 );
            $masked_destination      = AuthenticationController . phpsubstr($user->mobile, 0, $mobile_no_length - 11) . '****' . substr( $user->mobile, - 2 );


            if($user['otp'] && (strtotime($user['otp_expire_time']) > time())){
                $msg = "OTP has already been send to $masked_destination.";
                return response()->json(['responseCode'=>1,'msg'=>$msg]);
            }

            $otp = rand(99999,1000000);
            $message= "Your OTP is: $otp. \n". env('APP_NAME');

            $res = SendSMS::sendFastSMS($user->mobile,$message);
            if($res['status']==200){
                User::where('email',$email)->update([
                    'otp' => $otp,
                    'otp_expire_time' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                ]);
            }
            $msg = "OTP has been send to $masked_destination";

            return response()->json(['responseCode'=>1,'msg'=>$msg]);
        }
        catch(\Exception $e){
            #dd($e->getMessage(), $e->getFile(), $e->getLine());
            $msg = 'Error Occurred';
            return response()->json(['responseCode'=>-1,'msg'=>$msg]);
        }
    }
    public function verifyOTP(Request $request)
    {
        try {
            $email = $request['email'];
            $otp = $request['otp'];
            $user = User::where('email',$email)->first(['mobile','otp','otp_expire_time']);
            if(!$user){
                $msg = 'User not found';
                return response()->json(['responseCode'=>-1,'msg'=>$msg]);
            }

            if($user['otp'] == $otp && (strtotime($user['otp_expire_time']) > time())){
                $msg = "OTP matched";
                return response()->json(['responseCode'=>1,'msg'=>$msg]);
            }
            else{
                $msg = "OTP not matched";
                return response()->json(['responseCode'=>-1,'msg'=>$msg]);
            }
        }
        catch(\Exception $e){
            $msg = 'Error Occurred';
            return response()->json(['responseCode'=>-1,'msg'=>$msg]);
        }
    }

    public function changePass(Request $request)
    {
       try{
           $email = $request['email'];
           $password = $request['password'];
           $user = User::where('email',$email)->first();
           if(!$user){
               $msg = 'User not found';
               return response()->json(['responseCode'=>-1,'msg'=>$msg]);
           }
           User::where('email',$email)->update([
               'password' => Hash::make($password),
               'otp' => null,
               'otp_expire_time' => null,
           ]);
           $msg = "Password changed successfully";
           Session::flash('message',$msg);
           return response()->json(['responseCode'=>1,'msg'=>$msg]);
       }
       catch (\Exception $e){
           $msg = 'Password cannot be changed';
           Session::flash('error',$msg);
           return response()->json(['responseCode'=>-1,'msg'=>$msg]);
       }
    }
}
