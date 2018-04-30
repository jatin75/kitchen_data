<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminHomeController;
date_default_timezone_set('UTC');
use DB;
use URL;
use Session;
use Mail;
use Validator;
use App\Employee;
use App\LoginType;
use App\Admin;
use Hash;

class EmployeesController extends Controller
{
	public function index() {
		$employee = DB::select("SELECT au.first_name,au.last_name,au.phone_number,au.email,au.login_type_id,au.created_at,au.id,lt.type_name
			FROM admin_users AS au
			JOIN login_types AS lt ON lt.login_type_id = au.login_type_id WHERE au.is_deleted = 0 AND au.login_type_id <> 9");
		return view('admin.employee')->with('employeeList',$employee);
	}

	public function create() {
		return view('admin.addemployee')->with('employeeTypes',LoginType::all());
	}

	public function store(Request $request) {
		$hidden_employeeID = $request->get('hidden_employeeId');
		$employee_firstName = $request->get('employee_firstName');
		$employee_lastName = $request->get('employee_lastName');
		$employee_contactNo = $request->get('employee_contactNo');
		$employee_email = $request->get('employee_email');
		$employee_type = $request->get('employee_type');
		if(!empty($hidden_employeeID)) {

			$checkEmailExist = Admin::selectRaw('email')->where('email',$employee_email)->where('id','<>',$hidden_employeeID)->first();
			if(isset($checkEmailExist->email)) {
				$response['key'] = 3;
				echo json_encode($response);
			} else {
				$hidden_employeeEmail = $request->get('hidden_employeeEmail');
				$getDetail = Admin::where('id',$hidden_employeeID)->first();
				$getSessionEmail = Session::get('email');
				if($getSessionEmail == $getDetail->email) {
					Session::pull('name');
					Session::put('name',$employee_firstName.' '.$employee_lastName);
					$response['name'] = $employee_firstName.' '.$employee_lastName;
				}

				if($getSessionEmail != $hidden_employeeEmail) {
					$getDetail->login_type_id = $employee_type;
				}
				$getDetail->first_name = $employee_firstName;
				$getDetail->last_name = $employee_lastName;
				$getDetail->phone_number = (new AdminHomeController)->replacePhoneNumber($employee_contactNo);
				$getDetail->email = $employee_email;
				$getDetail->save();

				$response['key'] = 2;
				//Session::put('successMessage', 'Employee detail has been updated successfully.');
				echo json_encode($response);
			}
		}else {
			$checkEmailExist = Admin::selectRaw('email')->where('email',$employee_email)->first();
			if(isset($checkEmailExist->email)) {
				$response['key'] = 3;
				echo json_encode($response);
			} else {
				$employeeId = (new AdminHomeController)->getuserid();
				$employee_password = $employeeId;
				$objEmployee = new Admin();
				$objEmployee->id = $employeeId;
				$objEmployee->first_name = $employee_firstName;
				$objEmployee->last_name = $employee_lastName;
				$objEmployee->email = $employee_email;
				$objEmployee->password = Hash::make($employee_password);
				$objEmployee->phone_number = (new AdminHomeController)->replacePhoneNumber($employee_contactNo);
				$objEmployee->login_type_id = $employee_type;
				$objEmployee->save();

				/*send Mail*/

				/*Mail::send('emails.AdminPanel_EmployeeCreated',array(
					'password' => $employee_password,
					'email' => $employee_email,
				), function($message)use($employee_email){
					$message->from(env('FromMail','kitchen@gmail.com'),'KITCHEN');
					$message->to($employee_email)->subject('KITCHEN | Employee Account Created');

				});*/

				$response['key'] = 1;
				Session::put('successMessage', 'Employee detail has been added successfully.');
				echo json_encode($response);
			}
		}
	}

	public function edit($employee_id) {
		$getEmployeeDetail = Admin::selectRaw('first_name,last_name,phone_number,email,login_type_id,id')->where('id',$employee_id)->get();
		if(sizeof($getEmployeeDetail) > 0)
		{
			$getEmployeeDetail = $getEmployeeDetail[0];
		}
		return view('admin.addemployee')->with('employeeDetail',$getEmployeeDetail)->with('employeeTypes',LoginType::all());
	}

	public function destroy($employee_id)
	{
		Admin::where('id',$employee_id)->update(['is_deleted' => 1]);
		$msg = 'Employee deleted successfully.';
		Session::flash('successMessage',$msg);
		return back();
	}

	public function editMyProfile($email){
		$getEmployeeDetail = Admin::selectRaw('first_name,last_name,phone_number,email,login_type_id,id')->where('email',$email)->get();
		if(sizeof($getEmployeeDetail) > 0)
		{
			$getEmployeeDetail = $getEmployeeDetail[0];
		}
		if(!empty($getEmployeeDetail)) {
			return view('admin.addemployee')->with('employeeDetail',$getEmployeeDetail)->with('accountSetting',1)->with('employeeTypes',LoginType::all());
		}
	}

}
