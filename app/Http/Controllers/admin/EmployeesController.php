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
		$employee = Employee::selectRaw('first_name,last_name,phone_number,email,emp_type,created_at,id')->where('is_deleted',0)->get();
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
		$employee_password = $request->get('employee_password');
		$employee_type = $request->get('employee_type');

		if(!empty($hidden_employeeID)) {

			$checkEmailExist = Employee::selectRaw('email')->where('email',$employee_email)->where('id','<>',$hidden_employeeID)->first();
			if(isset($checkEmailExist->email) || isset($checkExist->email))
            {
                $response['key'] = 2;
				echo json_encode($response);
            } else {

	            $getDetail = Employee::where('id',$hidden_employeeID)->first();
				$getDetail->first_name = $employee_firstName;
				$getDetail->last_name = $employee_lastName;
				$getDetail->phone_number = (new AdminHomeController)->replacePhoneNumber($employee_contactNo);
				$getDetail->email = $employee_email;
				$getDetail->password = Hash::make($employee_password);
				$getDetail->emp_type = $employee_type;
				$getDetail->save();
				$response['key'] = 1;
				Session::put('successMessage', 'Employee detail has been updated successfully.');
				echo json_encode($response);
			}
		}else {
			$checkEmailExist = Employee::selectRaw('email')->where('email',$employee_email)->first();
			$checkExist = Admin::selectRaw('email')->where('email',$employee_email)->first();

			if(isset($checkEmailExist->email) || isset($checkExist->email))
            {
                $response['key'] = 2;
				echo json_encode($response);
            } else {

				$objEmployee = new Employee();
				$objEmployee->first_name = $employee_firstName;
				$objEmployee->last_name = $employee_lastName;
				$objEmployee->phone_number = (new AdminHomeController)->replacePhoneNumber($employee_contactNo);
				$objEmployee->email = $employee_email;
				$objEmployee->password = Hash::make($employee_password);
				$objEmployee->emp_type = $employee_type;
				$objEmployee->save();

				$objAdmin = new Admin();
				$objAdmin->name = $employee_firstName.' '.$employee_lastName;
				$objAdmin->email = $employee_email;
				$objAdmin->password = Hash::make($employee_password);
				$objAdmin->phone_number = (new AdminHomeController)->replacePhoneNumber($employee_contactNo);
				$objAdmin->login_type_id = $employee_type;
				if($employee_type != 1) {
					$objAdmin->is_super_admin = 0;
					$objAdmin->is_admin = 0;
				}else {
					$objAdmin->is_super_admin = 0;
					$objAdmin->is_admin = 1;
				}
				$objAdmin->save();

				$response['key'] = 1;
				Session::put('successMessage', 'Employee detail has been added successfully.');
				echo json_encode($response);
			}
		}
	}

	public function edit($employee_id){
		$getEmployeeDetail = Employee::selectRaw('first_name,last_name,phone_number,email,password,emp_type,id')->where('id',$employee_id)->get();
    	if(sizeof($getEmployeeDetail) > 0)
        {
        	$getEmployeeDetail = $getEmployeeDetail[0];
        }
        return view('admin.addemployee')->with('employeeDetail',$getEmployeeDetail)->with('employeeTypes',LoginType::all());

	}
}
