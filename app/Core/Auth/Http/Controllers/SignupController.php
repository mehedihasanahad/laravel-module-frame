<?php

namespace App\Core\Auth\Http\Controllers;

use App\Core\Auth\Notifications\Registered;
use App\Core\Auth\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\ETINverification;
use App\Libraries\ImageProcessing;
use App\Libraries\NIDverification;
use App\Libraries\SendMail;
use App\Libraries\Utility;
use App\Core\Signup\Models\UserVerificationData;
use App\Core\User\Models\User;
use App\Core\Users\Models\AreaInfo;
use App\Core\Users\Models\Countries;
use App\Core\Users\Models\Users;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use Carbon\Carbon;
use DB;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

class SignupController extends Controller
{

    public function identityVerifyMobile()
    {
        if (!ACL::getAccsessRight('user', 'E')) {
            abort('400', 'You have no access right!. Contact with system admin for more information.');
        }
        if (!Session::has('oauth_token') or !Session::has('oauth_data')) {
            Session::flash('error', 'You have no access right! This incidence will be reported. Contact with system admin for more information. [SC-1010]');
            return redirect()->to('/login');
        }
        try {
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->pluck('nicename', 'id')->toArray();
            $passport_types = [
                'diplomatic' => 'Diplomatic',
                'official' => 'Official',
                'ordinary' => 'Ordinary',
            ];
            $nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')
                ->pluck('nationality', 'id')->toArray();
            $passport_nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')->where('nationality', '!=', 'Bangladeshi')
                ->pluck('nationality', 'id')->toArray();

            $getPreviousVerificationData = UserVerificationData::where('user_email', Session::get('oauth_data')->user_email)->where('created_at', '>=', Carbon::now()->subDay())->first();

            return view('Signup::identity-verify-mobile', compact('countries', 'passport_types', 'nid_auth_token', 'etin_auth_token', 'nationalities', 'passport_nationalities', 'getPreviousVerificationData'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1011]');
            return \redirect()->back();
        }
    }

    public function identityVerify()
    {
        try {
            $userData = session('oauth_data');
            return view("auth.identity-verify", compact('userData'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1009]');
            return \redirect()->back();
        }
    }

    public function nidTinVerify(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $rules = [];
        $messages = [];

        if ($request->identity_type == 'nid') {
            $rules['nid_number'] = 'required|bd_nid';
        } elseif ($request->identity_type == 'tin') {
            $rules['etin_number'] = 'required|digits_between:10,15';
        }

        $rules['user_DOB'] = 'required|date|date_format:d-M-Y';

        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-200',
                'data' => [],
                'message' => $validation->getMessageBag()->toArray()
            ]);
        }

        try {
            Session::forget('nid_info');
            Session::forget('eTin_info');
            $nid_number = $request->get('nid_number');
            $etin_number = $request->get('etin_number');
            $user_DOB = $request->get('user_DOB');
            $identity_type = $request->get('identity_type');

            if ($identity_type == 'nid') {

                $identity_ver_response = $this->getNidResponse($nid_number, $user_DOB);
            } else {
                $identity_ver_response = $this->getTinResponse($etin_number, $user_DOB);
            }

            return response()->json($identity_ver_response);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-201',
                'data' => [],
                'message' => CommonFunction::showErrorPublic($e->getMessage() . '[SC-1008]')
            ]);
        }
    }

    public function getNidResponse($nid_number, $user_DOB)
    {
        try {
            $nid_data = [
                'nid_number' => $nid_number,
                'user_DOB' => $user_DOB,
            ];

            $nid_verification = new NIDverification();
            $nid_verify_response = $nid_verification->verifyNID($nid_data);

            $data = [];
            if (isset($nid_verify_response['status']) && $nid_verify_response['status'] === 'success') {

                $nid_verify_response['data']['nationalId'] = $nid_number; // WE ADD for new api ONLY FOR bscic
                $nid_verify_response['data']['gender'] = 'male'; // defult set mail
                // Add NID number with nid info
                Session::put('nid_info', Encryption::encode(json_encode($nid_verify_response['data'])));

                $data['nameEn'] = $nid_verify_response['data']['name'];
                $data['dob'] = $nid_verify_response['data']['dateOfBirth'];
                $data['photo'] = $nid_verify_response['data']['photo'];
                $data['gender'] = 'male';
                $nid_verify_response['data'] = $data;
                return $nid_verify_response;
            }
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1007]');
            return \redirect()->back();
        }
    }

    public function getTinResponse($etin_number, $user_DOB)
    {
        try {
            $etin_verification = new ETINverification();
            $etin_verify_response = $etin_verification->verifyETIn($etin_number);

            $data = [];
            if (isset($etin_verify_response['status']) && $etin_verify_response['status'] === 'success') {

                // Validate Date of birth
                if (date('d-M-Y', strtotime($etin_verify_response['data']['dob'])) != $user_DOB) {
                    return [
                        'status' => "error",
                        'statusCode' => 'SIGN-UP-203',
                        'data' => [],
                        'message' => 'Sorry! Invalid date of birth. Please provide valid information. [SC-1004]'
                    ];
                }

                // Add etin number with etin_info
                $etin_verify_response['data']['etin_number'] = $etin_number;
                Session::put('eTin_info', Encryption::encode(json_encode($etin_verify_response['data'])));

                // Re-arrange e-tin response
                // Send only some specific data
                $data['nameEn'] = $etin_verify_response['data']['assesName'];
                $data['father_name'] = $etin_verify_response['data']['fathersName'];
                $data['dob'] = $etin_verify_response['data']['dob'];
            }

            $etin_verify_response['data'] = $data;
            return $etin_verify_response;
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1006]');
            return \redirect()->back();
        }
    }

    /**
     * Identity verification and redirect to sign-up page
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function identityVerifyConfirm(Request $request)
    {
        try {
            $rollName = 'Applicant';
            $roll = (new CommonFunction())->getRollInfo($rollName, ['dashboard']);
            DB::beginTransaction();

            $userData = $request->all();

            if (!empty($userData['birth_date'])) {
                $userData['birth_date'] = date('Y-m-d', strtotime($userData['birth_date']));
            }
//            $userData['user_group_id'] = 6;
            $userData['user_group_id'] = $roll->id;

            /**
             * if user signup with Google then set user as active, approve and verified
             */
            if (Session::has('oauth_data')) {
                $oauth_data = Session::get('oauth_data');
                $userData['status'] = 1;
                $session_msg = trans('Auth::messages.welcome');
                $redirect_link = '/dashboard';
            } else {

                /**
                 * this section need to be developed later
                 * Encryption issue exist
                 */
                /*$token_no = hash('SHA256', "-" . $userData['email'] . "-");
                $encrypted_token = Encryption::encodeId($token_no);

                $receiverInfo[] = [
                    'user_email' => $userData['email'],
                    'user_mobile' => $userData['mobile']
                ];

                $appInfo = [
                    'verification_link' => url('users/verify-created-user/' . ($encrypted_token))
                ];

                CommonFunction::sendEmailSMS('CONFIRM_ACCOUNT', $appInfo, $receiverInfo);*/

                $session_msg = 'Account has been created successfully! An email has been sent to the email for account activation.';
                $redirect_link = '/';
            }

            $user = User::updateOrCreate([
                'email' => $userData['email']
            ], $userData);
            /* Assign applicant role to new user */
            $user->assignRole($rollName);
            /* Assign applicant role to new user */
            Auth::loginUsingId($user->id);
            Utility::entryAccessLog();

            DB::commit();
            // Forget session data
            Session::forget('oauth_data');

            Session::flash('success', $session_msg);
            return Redirect::to($redirect_link);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1005]');
            return \redirect()->back();
        }
    }

    /**
     * @param $verification_id
     * @return \Illuminate\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector\
     */
    public function identityVerifyConfirmWithPreviousData($verification_id)
    {
        try {
            $decoded_verification_id = Encryption::decodeId($verification_id);
            $user_verification_data = UserVerificationData::find($decoded_verification_id);
            if (empty($user_verification_data)) {
                Session::flash('error', 'Your previous verification data not found. Please try to sign up with the new verification. [SC-1003]');
                return \redirect()->back();
            }

            Session::put('nationality_type', $user_verification_data->nationality_type);
            Session::put('identity_type', $user_verification_data->identity_type);
            if ($user_verification_data->identity_type == 'tin') {
                Session::put('eTin_info', $user_verification_data->eTin_info);
            } elseif ($user_verification_data->identity_type == 'nid') {
                Session::put('nid_info', $user_verification_data->nid_info);
            } elseif ($user_verification_data->identity_type == 'passport') {
                Session::put('passport_info', $user_verification_data->passport_info);
            }
            return \redirect('client/signup/registration');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! something went wrong, Please try again. SIGN-UP-214');
            return \redirect()->back();
        }
    }

    public function getPassportData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        try {
            $base64_split = explode(',', substr($request->get('file'), 5), 2);
            $passport_verify_url = config('app.PASSPORT_VERIFY_URL');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $passport_verify_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $base64_split[1]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

            $result = curl_exec($ch);

            if (!curl_errno($ch)) {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            } else {
                $http_code = 0;
            }

            curl_close($ch);

            $response = json_decode($result);

            $returnData = [
                'success' => true,
                'code' => '',
                'msg' => '',
                'data' => []
            ];

            if (isset($response->code) && $response->code == '200') {
                $returnData['data'] = $response->data;
                if ($response->has_image == true) {
                    Session::put('passport_image', $response->text_image);
                }
                $returnData['code'] = '200';
                $returnData['nationality_id'] = Countries::where('iso3', 'like', '%' . $response->data->nationality . '%')->value('id');
            } elseif (isset($response->code) && in_array($response->code, ['400', '401', '405'])) {
                $returnData['msg'] = $response->msg;
                $returnData['code'] = $response->code;
            }

            // uncomment the below line for python API
            //unlink($file_temp_path);
            Session::put('passport_info', Encryption::encode(json_encode($returnData)));
            return response()->json($returnData);
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong ! [SC-1083]');
            return \redirect()->back();
        }
    }

    /**
     * User Registration and Password Set up email
     * @param RegisterRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            $rollName = 'Applicant';
            $roll = (new CommonFunction())->getRollInfo($rollName, ['dashboard']);
            $user->first_name = $data['name'];
            $user->user_group_id = $roll->id;
            $user->email = $data['email'];
            $user->division_id = $data['division_id'];
            $user->district_id = $data['district_id'];
            $user->upzila_id = $data['upzila_id'];
            $user->mobile = $data['mobile'];
            $user->national_id = $data['national_id'];
            $user->status = 1;
            $user->save();
            /* Assign applicant role to new user */
            $user->assignRole($rollName);
            /* Assign applicant role to new user */

            $temporaryUrl = URL::temporarySignedRoute(
                'set-password',
                now()->addMinutes(30),
                ['user' => Encryption::encodeId($user->id)]
            );

            $email = EmailTemplate::where('caption', 'password-setup')->first();

            $emailText = str_replace('{$temporaryUrl}', $temporaryUrl, $email->email_content);

            // send email to user

            Session::flash('success', 'We have sent an email to setup your password');
            return redirect()->route('login');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
