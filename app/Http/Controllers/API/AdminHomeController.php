<?php

namespace App\Http\Controllers\API;

date_default_timezone_set('UTC');
use App\Admin;
use App\ApiAdmin;
use App\Chat;
use App\Http\Controllers\admin\JobsController;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Validator;

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
                    $success['email'] = $user->email;
                    $success['phone_number'] = (!empty($user->phone_number)) ? $this->formatPhoneNumber($user->phone_number) : null;
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

    /*forget password*/
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
            }

            $email = $request->input('email');
            $checkEmail = ApiAdmin::where('email', $email)->first();
            if (!empty($checkEmail)) {
                $temporaryPwd = str_random(8);
                ApiAdmin::where('email', $email)->update(['password' => Hash::make($temporaryPwd)]);

                try {
                    Mail::send('emails.AdminPanel_ForgotPassword', array(
                        'temp_password' => $temporaryPwd,
                    ), function ($message) use ($email) {
                        $message->from(env('FromMail', 'askitchen18@gmail.com'), 'A&S KITCHEN');
                        $message->to($email)->subject('A&S KITCHEN | Forgot Password');
                    });
                } catch (\Exception $e) {
                    return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Something went wrong. Please try again."]);
                }
                return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "An email containing your temporary login password has been sent to your verified email address. You can change your password from your profile."]);
            } else {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Email is incorrect. Please try again."]);
            }
        } catch (\Exception $e) {}
    }

    /*change password*/
    public function changeAccountSetting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
                'retype_Password' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
            }
            $current_password = $request->get('current_password');
            $new_password = $request->get('new_password');
            $retype_password = $request->get('retype_Password');
            $user_id = $request->get('user_id');

            if ($new_password != $retype_password) {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "New password and  Retype password is not match. Please try again."]);
            }

            $checkPassword = ApiAdmin::where('id', $user_id)->first();
            if (!empty($checkPassword)) {
                if (Hash::check($current_password, $checkPassword->password)) {
                    $checkPassword->password = Hash::make($new_password);
                    $checkPassword->save();
                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Your password has been changed successfully."]);
                } else {
                    return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Current password is invalid. Please try again."]);
                }
            } else {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Something went wrong. Please try again."]);
            }
        } catch (\Exception $e) {}
    }

    /*change myprofile*/
    public function changeMyProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'login_type_id' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'contact_no' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
            }

            $user_id = $request->get('user_id');
            $firstName = $request->get('first_name');
            $lastName = $request->get('last_name');
            $contactNo = $request->get('contact_no');
            $email = $request->get('email');
            $login_type_id = $request->get('login_type_id');

            $checkEmailExist = ApiAdmin::selectRaw('email')->where('email', $email)->where('id', '<>', $user_id)->first();
            if (isset($checkEmailExist->email)) {
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Entered email address already exists."]);
            }

            $getDetail = ApiAdmin::where('id', $user_id)->first();
            $getDetail->first_name = $firstName;
            $getDetail->last_name = $lastName;
            $getDetail->phone_number = $this->replacePhoneNumber($contactNo);
            $getDetail->email = $email;
            $getDetail->save();

            $success['token'] = "Bearer " . $getDetail->createToken('kitchen')->accessToken;
            $success['user_id'] = $user_id;
            $success['user_name'] = $firstName . ' ' . $lastName;
            $success['email'] = $email;
            $success['phone_number'] = (!empty($contactNo)) ? $this->formatPhoneNumber($contactNo) : null;
            $success['login_type_id'] = $login_type_id;

            return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Profile has been updated successfully.", 'response_data' => $success]);
        } catch (\Exception $e) {}
    }

    /* Chat module */
    public function chatPost(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sender_id' => 'required',
                'receiver_id' => 'required',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
            }
            $sender_id = $request->get('sender_id');
            $receiver_id = $request->get('receiver_id');
            $message = $request->get('message');

            if (!empty($message)) {
                $objChat = new Chat;
                $objChat->sender_id = $sender_id;
                $objChat->receiver_id = $receiver_id;
                $objChat->message = $message;
                $objChat->is_deleted = 0;
                $objChat->created_at = $created_at = date('Y-m-d H:i:s');
                $objChat->save();

                /*send notification to receiver */
                if (!empty($receiver_id)) {
                    $title = 'New Message Received';
                    $badge = '1';
                    $sound = 'default';
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id', $receiver_id)->first();
                    if (!empty($device_detail->device_token)) {
                        $messageBody = $message;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        (new JobsController)->pushNotification($deviceid, $device_type, $messageBody, $title, $badge, $sound);
                    }
                }
                $success['created_at'] = $created_at;
                return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Chat meassage has been added successfully.", 'response' => $success]);
            }
        } catch (\Exception $e) {}
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
