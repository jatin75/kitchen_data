<?php

namespace App\Http\Controllers\admin;
date_default_timezone_set('UTC');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use URL;
use Hash;
use Session;
use Mail;
use Validator;
use App\Admin;
use App\JobType;
use App\Job;

class AdminHomeController extends Controller
{
	/*show login*/
	public function showLogin()
	{
		return view('admin.login');
	}

	/*do login*/
	public function doLogin(Request $request){

		$email = $request->input('admin_email');
		$password = $request->input('admin_password');
		$checkLogin = Admin::where('email',$email)->where('is_deleted',0)->whereIn('login_type_id', [1, 2, 9])->first();
		if(!empty($checkLogin)) {
			if($checkLogin->password == md5($password) || Hash::check($password, $checkLogin->password)) {
				Session::put('employee_id', $checkLogin->id);
				Session::put('name',$checkLogin->first_name.' '.$checkLogin->last_name);
				Session::put('email',$checkLogin->email);
				Session::put('login_type_id',$checkLogin->login_type_id);
				return redirect()->route('dashboard');
			}else {
				Session::flash('invalid', 'Invalid email or password combination. Please try again.');
				return back();
			}

		}else {
			Session::flash('invalid', 'Invalid email or password combination. Please try again.');
			return back();
		}
	}

	/*showdashboard*/
	public function showDashboard(){
		$getJobTypeDetail = JobType::selectRaw('job_status_id,job_status_name')->get();
		return view('admin.dashboard')->with('jobTypeDetails',$getJobTypeDetail);
	}

	public function logout()
	{
		Session::flush();
		/*Session::put('email','');*/
		return redirect()->route('login');
	}

	public function showForgotPassword(){
		return view('admin.forgot_password');
	}

	public function sendForgotPasswordEmail(Request $request){
		$email = $request->input('txtemail');
		$checkEmail = Admin::where('email',$email)->first();
		if(!empty($checkEmail)){
			$temporaryPwd = str_random(8);
			Admin::where('email',$email)->update(['password'=>Hash::make($temporaryPwd)]);

			try{
				Mail::send('emails.AdminPanel_ForgotPassword',array(
					'temp_password' => $temporaryPwd
				), function($message)use($email){
					$message->from(env('FromMail','kitchen@gmail.com'),'KITCHEN');
					$message->to($email)->subject('KITCHEN | Forgot Password');
				});
			} catch (\Exception $e){
				Session::flash('invalidMail', 'Something went wrong. Please try again.');
				return back();
			}
			Session::flash('validMail', 'An email containing your temporary login password has been sent to your verified email address. You can change your password from your profile.');
			return back();
		}
	}

	public function editMyProfile($email){
		$getAdminDetail = Admin::where('email',$email)->first();
		if(!empty($getAdminDetail)) {
			return view('admin.adminprofile')->with('adminDetail',$getAdminDetail)->with('accountSetting',1);
		}
	}

	public function store(Request $request) {
		$hidden_adminID = $request->get('hidden_adminId');
		$admin_firstName = $request->get('admin_firstName');
		$admin_lastName = $request->get('admin_lastName');
		$admin_contactNo = $request->get('admin_contactNo');
		$admin_email = $request->get('admin_email');

		$checkEmailExist = Admin::selectRaw('email')->where('email',$admin_email)->where('id','<>',$hidden_adminID)->where('is_deleted','<>',1)->first();
		if(isset($checkEmailExist->email)) {
			$response['key'] = 2;
			echo json_encode($response);
		} else {
			$getDetail = Admin::where('id',$hidden_adminID)->first();
			$getSessionEmail = Session::get('email');
			if($getSessionEmail == $getDetail->email) {
				Session::pull('name');
				Session::put('name',$admin_firstName.' '.$admin_lastName);
				$response['name'] = $admin_firstName.' '.$admin_lastName;
			}
			$getDetail->first_name = $admin_firstName;
			$getDetail->last_name = $admin_lastName;
			$getDetail->phone_number = (new AdminHomeController)->replacePhoneNumber($admin_contactNo);
			$getDetail->email = $admin_email;
			$getDetail->save();

			$response['key'] = 1;
			//Session::put('successMessage', 'Admin detail has been updated successfully.');
			echo json_encode($response);
		}
	}

	public function changePassword(Request $request) {

		$current_password = $request->get('current_password');
		$new_password = $request->get('new_password');
		$hidden_email = $request->get('hidden_email');
		$checkPassword = Admin::where('email',$hidden_email)->first();
		if(!empty($checkPassword)) {
			if(Hash::check($current_password,$checkPassword->password)) {
				$checkPassword->password = Hash::make($new_password);
				$checkPassword->save();
				return 1;
			}else {
				return 2;
			}
		}
	}

	public function showJobDetails(Request $request) {
		$getSessionEmail = Session::get('email');
		$job_statusId = $request->get('jobStatusId');
		if($job_statusId == 0) {
			$jobStatusCond = '';
		}else {
			$jobStatusCond = "AND jb.job_status_id = {$job_statusId}";
		}
		
		$getJobDetails = DB::select("SELECT jb.job_title,jb.super_name,jb.start_date,jb.end_date,jb.company_clients_id,cmp.name,jt.job_status_name FROM jobs AS jb JOIN companies AS cmp ON cmp.company_id = jb.company_id JOIN job_types AS jt ON jt.job_status_id = jb.job_status_id WHERE jb.is_deleted = 0 And jb.is_active = 1 {$jobStatusCond}");

		$html = '';
		$html .= '<table id="jobList" class="display nowrap" cellspacing="0" width="100%">
		<thead>
		<tr>
		<th>Job Name</th>
		<th>Company Name</th>
		<th>Status</th>
		<th>Start Date</th>
		<th>Expected Completion Date</th>
		</tr>
		</thead>
		<tbody>';
		if(!empty($getJobDetails)) {
			if(Session::get('login_type_id') == 9) {
				foreach($getJobDetails as $jobDetail) {
					$getDetail = Admin::where('email',$getSessionEmail)->first();
					$session_userId = $getDetail->id;
					$client_id_array = explode(',', $jobDetail->company_clients_id);
					if(in_array($session_userId, $client_id_array)) {

						$html .='<tr>
						<td>'.$jobDetail->job_title.'</td>
						<td>'.$jobDetail->name.'</td>
						<td>'.$jobDetail->job_status_name.'</td>
						<td>'.date('m/d/Y',strtotime($jobDetail->start_date)).'</td>
						<td>'.date('m/d/Y',strtotime($jobDetail->end_date)).'</td>
						</tr>';
					}
				}
			}else {
				foreach($getJobDetails as $jobDetail) {
					$html .='<tr>
					<td>'.$jobDetail->job_title.'</td>
					<td>'.$jobDetail->name.'</td>
					<td>'.$jobDetail->job_status_name.'</td>
					<td>'.date('m/d/Y',strtotime($jobDetail->start_date)).'</td>
					<td>'.date('m/d/Y',strtotime($jobDetail->end_date)).'</td>
					</tr>';
				}
			}
		}
		$html .='</tbody>
		</table>';

		$response['html'] = $html;
		echo json_encode($response);
	}

	function replacePhoneNumber($phone_number)
	{
		$replace_phone_number = preg_replace('/\D/', '', $phone_number);
		return $replace_phone_number;
	}

	function formatPhoneNumber($phone_number)
	{
		$replace_phone_number = preg_replace('/\D/', '', $phone_number);
		$format_phone_number = substr_replace(substr_replace(substr_replace($replace_phone_number, '(', 0,0), ') ', 4,0), ' - ', 9,0);
		return $format_phone_number;
	}

	function getuserid() {
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 3; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$userid = $randomString.mt_rand(10000,99999);
		$check = Admin::where('id',$userid)->first();
		if (empty($check)){
			return $userid;
		} else {
			$this->getuserid();
		}
	}
}