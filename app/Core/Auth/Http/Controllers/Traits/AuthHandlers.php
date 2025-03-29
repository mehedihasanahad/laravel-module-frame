<?php

namespace App\Core\Auth\Http\Controllers\Traits;

use App\Libraries\Encryption;
use App\Libraries\Utility;
use App\Models\User;
use App\Models\UserLogs;
use App\Core\Auth\Models\FailedLogin;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

trait AuthHandlers
{
//    private function _checkAttack($request)
//    {
//        try {
//            $ip_address = $request->ip();
//            $user_email = $request->get('email');
//            $count = FailedLogin::where('remote_address', "$ip_address")
//                ->where('user_email', $user_email)
//                ->where('is_archived', 0)
//                ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -20 MINUTE)'))
//                ->count();
//
//            if ($count > 20) {
//                Session::flash('error', 'Invalid Login session. Please try after 10 to 20 minute [AH:001]');
//                return false;
//            }
//            else {
//                $count = FailedLogin::where('remote_address', "$ip_address")
//                    ->where('user_email', $user_email)
//                    ->where('is_archived', 0)
//                    ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -60 MINUTE)'))
//                    ->count();
//
//                if ($count > 40) {
//                    Session::flash('error', 'Invalid Login session. Please try after 30 to 60 minute [AH:002]');
//                    return false;
//                }
//                else {
//                    $count = FailedLogin::where('user_email', $user_email)
//                        ->where('is_archived', 0)
//                        ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -10 MINUTE)'))
//                        ->count();
//                    if ($count > 6) {
//                        Session::flash('error', 'Invalid Login session. Please try after 5 to 10 minute [AH:003]');
//                        return false;
//                    }
//                }
//            }
//        } catch (\Exception $e) {
//            Session::flash('error', 'Login session exception. Please try after 5 to 10 minute [AH:004]');
//            return false;
//        }
//        return true;
//    }

    /**
     * Check brute force attacker
     * @param Request $request user request body
     * @return boolean
     */
    private function checkBruteForceAttack(Request $request): bool
    {
        try {
            $ip_address = $request->ip();
            $user_email = $request->get('email');
            $count = $this->failedLoginCount($user_email, '20 MINUTE', $ip_address);
            if ($count > 20) {
                Session::flash('error', 'Invalid Login session. Please try after 10 to 20 minute [AH:001]');
                return false;
            } else {
                $count = $this->failedLoginCount($user_email, '60 MINUTE', $ip_address);

                if ($count > 40) {
                    Session::flash('error', 'Invalid Login session. Please try after 30 to 60 minute [AH:002]');
                    return false;
                } else {
                    $count = $this->failedLoginCount($user_email, '10 MINUTE');

                    if ($count > 6) {
                        Session::flash('error', 'Invalid Login session. Please try after 5 to 10 minute [AH:003]');
                        return false;
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Login session exception. Please try after 5 to 10 minute [AH:004]');
            return false;
        }
        return true;
    }

    /**
     * Check brute force attacker
     * @param string $user_email user email
     * @param string $interval_date_time interval date or time
     * @param string $ip_address user ip address. Optional
     * @return int
     */
    private function failedLoginCount(string $user_email, string $interval_date_time, string $ip_address = '')
    {
//        $interval = 'DATE_ADD(now(), INTERVAL '.$operator.' '.$interval_date_time.')';
        $interval = Carbon::now()->sub($interval_date_time);
        return FailedLogin::when(!empty($ip_address), function ($query) {
            global $ip_address;
            $query->where('remote_address', "$ip_address");
        })
            ->where('user_email', $user_email)
            ->where('is_archived', 0)
            ->where('created_at', '>', $interval)
            ->count();
    }


    /**
     *
     * @param $request
     * @return void
     */
    private function _failedLogin($request)
    {
        $ip_address = $request->ip();
        $user_email = $request->get('email');
        FailedLogin::create(['remote_address' => $ip_address, 'user_email' => $user_email]);
    }

    public static function killUserSession($user_id)
    {
        $sessionID = User::where('id', $user_id)->pluck('auth_token');
        if (!empty($sessionID)) {
            $sessionID = Encryption::decode($sessionID);
            Session::getHandler()->destroy($sessionID);
        }
        User::where('id', $user_id)->update(['login_token' => Encryption::encode(Session::getId())]);
    }


    public function _checkUserTypeRootActivation()
    {
        $userGroupData = Auth::user()->userGroup;
        if (!$userGroupData->status) {
            Auth::logout();
            Session::flash('error', trans('Auth::messages.login_off') . ' [L01]');
            return false;
        }
        return true;
    }

    private function isValidForLogin()
    {
        $user_logs = UserLogs::where('user_id', '=', Auth::user()->id)->orderBy('user_logs.id', 'desc')->first(['login_dt']);
        if ($user_logs == null) {
            return true;
        }
        $last_login = date('Y-m-d H:i:s', strtotime($user_logs->login_dt));
        $interval = Utility::dayIntervalByCurDate($last_login);
        $MAX_IDLE_DAY_FOR_LOGIN = env('MAX_IDLE_DAY_FOR_LOGIN');
        if ($interval > $MAX_IDLE_DAY_FOR_LOGIN) {
            return false;
        }
        return true;
    }

}
