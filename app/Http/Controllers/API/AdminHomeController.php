<?php

namespace App\Http\Controllers\API;

date_default_timezone_set('UTC');
use App\ApiAdmin;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Mail;

class AdminHomeController extends Controller
{
    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
            }
            $email = $request->get('email');
            $password = $request->get('password');
            $device_token = $request->get('device_token');
            $device_type = $request->get('device_type');
            $user = ApiAdmin::where('email', $email)->where('is_deleted', 0)->first();
            if (!empty($user)) {
                if ($user->password == md5($password) || Hash::check($password, $user->password)) {
                    $success['token'] = "Bearer " . $user->createToken('kitchen')->accessToken;
                    $success['user_id'] = $user->id;
                    $success['user_name'] = $user->first_name . ' ' . $user->last_name;
                    $success['login_type_id'] = $user->login_type_id;
                    $user->device_token = $device_token;
                    $user->device_type = $device_type;
                    $user->save();

                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Token generated successfully", 'response_data' => $success]);
                } else {
                    return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Email or password is incorrect. Please try again."]);
                }

            } else {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Email or password is incorrect. Please try again."]);
            }
        } catch (\Exception $e) {}
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function userLogout()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                if (isset($user->id)) {
                    ApiAdmin::where('id', $user->id)->update(['device_type' => null, 'device_token' => null]);
                }
                return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "User logout successfully."]);
            } else {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Something went wrong."]);
            }
        } catch (\Exception $e) {}
    }

    public function forgotPassword(Request $request) {

        try {
            $email = $request->input('email');
            $checkEmail = ApiAdmin::where('email',$email)->first();
            if(!empty($checkEmail)){
                $temporaryPwd = str_random(8);
                ApiAdmin::where('email',$email)->update(['password'=>Hash::make($temporaryPwd)]);

                try{
                    Mail::send('emails.AdminPanel_ForgotPassword',array(
                        'temp_password' => $temporaryPwd
                    ), function($message)use($email){
                        $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                        $message->to($email)->subject('A&S KITCHEN | Forgot Password');
                    });
                } catch (\Exception $e){
                    return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Something went wrong. Please try again."]);
                }
                return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "An email containing your temporary login password has been sent to your verified email address. You can change your password from your profile."]);
            }else {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Email is incorrect. Please try again."]);
            }
        }catch (\Exception $e) {}
    }

    public function replacePhoneNumber($phone_number)
    {
        $replace_phone_number = preg_replace('/\D/', '', $phone_number);
        return $replace_phone_number;
    }

    public function formatPhoneNumber($phone_number)
    {
        $replace_phone_number = preg_replace('/\D/', '', $phone_number);
        $format_phone_number = substr_replace(substr_replace(substr_replace($replace_phone_number, '(', 0, 0), ') ', 4, 0), ' - ', 9, 0);
        return $format_phone_number;
    }

    public function getuserid()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $userid = $randomString . mt_rand(10000, 99999);
        $check = ApiAdmin::where('id', $userid)->first();
        if (empty($check)) {
            return $userid;
        } else {
            $this->getuserid();
        }
    }
}
