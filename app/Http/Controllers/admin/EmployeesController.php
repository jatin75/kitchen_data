<?php

namespace App\Http\Controllers\admin;

use App\Admin;
use App\Http\Controllers\admin\AdminHomeController;
date_default_timezone_set('UTC');
use App\Http\Controllers\Controller;
use App\LoginType;
use DB;
use Hash;
use Illuminate\Http\Request;
use Session;
use Mail;

class EmployeesController extends Controller
{
    public function index()
    {
        $employee = DB::select("SELECT au.first_name,au.last_name,au.phone_number,au.email,au.login_type_id,au.created_at,au.id,lt.type_name,au.secondary_phone_number,au.secondary_email
			FROM admin_users AS au
            JOIN login_types AS lt ON lt.login_type_id = au.login_type_id WHERE au.is_deleted = 0 AND au.login_type_id <> 9 ORDER BY au.created_at DESC");
        if(Session::get('login_type_id') == 1  || Session::get('login_type_id') == 2 ) {
            return view('admin.employee')->with('employeeList', $employee);
        }else {
            return redirect(route('dashboard'));
        }
    }

    public function create()
    {
        $loginType = LoginType::where('login_type_id','<>',9)->get();
        return view('admin.addemployee')->with('employeeTypes', $loginType);
    }

    public function store(Request $request)
    {
        $adminHomeController = (new AdminHomeController);
        $hidden_employeeID = $request->get('hidden_employeeId');
        $employee_firstName = $request->get('employee_firstName');
        $employee_lastName = $request->get('employee_lastName');
        $employee_contactNo = $request->get('employee_contactNo');
        $employee_email = $request->get('employee_email');
        $employee_type = $request->get('employee_type');
        $employee_password = $request->get('employee_password');
        $employee_secondPhone = $request->get('employee_secondPhone');
        $employee_secondEmail = $request->get('employee_secondEmail');

        if (!empty($hidden_employeeID)) {
            /* Edit */
            $checkEmailExist = Admin::selectRaw('email')->where('email', $employee_email)->where('id', '<>', $hidden_employeeID)->first();
            if (isset($checkEmailExist->email)) {
                $response['key'] = 3;
                echo json_encode($response);
            } else {
                $hidden_employeeEmail = $request->get('hidden_employeeEmail');
                $getDetail = Admin::where('id', $hidden_employeeID)->first();
                $getSessionEmail = Session::get('email');
                if ($getSessionEmail == $getDetail->email) {
                    Session::pull('name');
                    Session::put('name', $employee_firstName . ' ' . $employee_lastName);
                    Session::pull('email');
                    Session::put('email',$employee_email);
                    $response['name'] = $employee_firstName . ' ' . $employee_lastName;
                }

                if ($getSessionEmail != $hidden_employeeEmail) {
                    $getDetail->login_type_id = $employee_type;
                }
                $getDetail->first_name = $employee_firstName;
                $getDetail->last_name = $employee_lastName;
                $getDetail->phone_number = $adminHomeController->replacePhoneNumber($employee_contactNo);
                $getDetail->email = $employee_email;
                if(empty($employee_secondPhone) || $employee_secondPhone == '') {
                    $getDetail->secondary_phone_number = '';
                }else {
                    $getDetail->secondary_phone_number = $adminHomeController->replacePhoneNumber($employee_secondPhone);
                }
                $getDetail->secondary_email = $employee_secondEmail;
                $getDetail->save();

                $response['key'] = 2;
                echo json_encode($response);
            }
        } else {
            /* Create */
            $checkEmailExist = Admin::selectRaw('email')->where('email', $employee_email)->where('is_deleted', 0)->first();
            if (isset($checkEmailExist->email)) {
                $response['key'] = 3;
                echo json_encode($response);
            } else {
                $employeeId = $adminHomeController->getuserid();
                $objEmployee = new Admin();
                $objEmployee->id = $employeeId;
                $objEmployee->first_name = $employee_firstName;
                $objEmployee->last_name = $employee_lastName;
                $objEmployee->email = $employee_email;
                $objEmployee->password = Hash::make($employee_password);
                $objEmployee->phone_number = $adminHomeController->replacePhoneNumber($employee_contactNo);
                if(empty($employee_secondPhone) || $employee_secondPhone == '') {
                    $objEmployee->secondary_phone_number = '';
                }else {
                    $objEmployee->secondary_phone_number = $adminHomeController->replacePhoneNumber($employee_secondPhone);
                }
                $objEmployee->secondary_email = $employee_secondEmail;
                $objEmployee->login_type_id = $employee_type;
                $objEmployee->created_at = date('Y-m-d H:i:s');
                $objEmployee->save();

                /*send Mail*/
                 Mail::send('emails.AdminPanel_EmployeeCreated',array(
                'password' => $employee_password,
                'email' => $employee_email,
                ), function($message)use($employee_email){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->to($employee_email)->subject('A&S KITCHEN | Employee Account Created');
                });

                $response['key'] = 1;
                Session::put('successMessage', 'Employee detail has been added successfully.');
                echo json_encode($response);
            }
        }
    }

    public function edit($employee_id)
    {
        $getEmployeeDetail = Admin::selectRaw('first_name,last_name,phone_number,email,login_type_id,id,secondary_phone_number,secondary_email')->where('id', $employee_id)->get();
        if (sizeof($getEmployeeDetail) > 0) {
            $getEmployeeDetail = $getEmployeeDetail[0];
        }
        $loginType = LoginType::where('login_type_id','<>',9)->get();
        return view('admin.addemployee')->with('employeeDetail', $getEmployeeDetail)->with('employeeTypes', $loginType);
    }

    public function destroy($employee_id)
    {
        Admin::where('id', $employee_id)->update(['is_deleted' => 1]);
        $msg = 'Employee deleted successfully.';
        Session::flash('successMessage', $msg);
        return back();
    }

    public function editMyProfile($id)
    {
        $getEmployeeDetail = Admin::selectRaw('first_name,last_name,phone_number,email,login_type_id,id')->where('id', $id)->get();
        if (sizeof($getEmployeeDetail) > 0) {
            $getEmployeeDetail = $getEmployeeDetail[0];
        }
        if (!empty($getEmployeeDetail)) {
            return view('admin.addemployee')->with('employeeDetail', $getEmployeeDetail)->with('accountSetting', 1)->with('employeeTypes', LoginType::all());
        }
    }

}