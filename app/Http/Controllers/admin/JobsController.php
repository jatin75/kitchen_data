<?php

namespace App\Http\Controllers\admin;

use App\Admin;
date_default_timezone_set('UTC');
use App\Company;
use App\Http\Controllers\Controller;
use App\Job;
use DB;
use Illuminate\Http\Request;
use Session;

class JobsController extends Controller
{
    public function index()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,job_status_id,start_date,end_date')->where('is_active', 1)->where('is_deleted', 0)->get();
        return view('admin.jobs')->with('jobDetails', $getJobDetails);
    }

    public function showDeactivated()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,job_status_id,start_date,end_date')->where('is_active', 0)->where('is_deleted', 0)->get();
        return view('admin.deactivatedjobs')->with('jobDetails', $getJobDetails);
    }

    public function create()
    {
        $employeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0");
        $comapnyList = Company::selectRaw('company_id,name')->where('is_deleted', 0)->get();
        $stoneEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 6");
        $installEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 5");

        return view('admin.addjob')->with('jobDetails', Job::all())->with('employeeList', $employeeList)->with('comapnyList', $comapnyList)->with('stoneEmployeeList', $stoneEmployeeList)->with('installEmployeeList', $installEmployeeList);
    }

    public function edit($job_id)
    {
        return view('admin.deactivatedjobs');
    }

    public function store(Request $request)
    {
        $hidden_job_id = $request->get('hidden_job_id');
        // $working_employees = implode(',', $request->get('working_employee_id'));
        // $comapny_clients = implode(',', $request->get('comapny_clients_id'));
        // $installation_employees = implode(',', $request->get('installation_employees_id'));
        // $stone_installation_employees = implode(',', $request->get('stone_installation_employees_id'));

        $a = date('h:i:A', strtotime('12:00:00'));

        echo '<pre>';
        print_r($a);die;

        // $stone_installation_date = $request->get('');
        // $stone_installation_time = $request->get('');

        if (!empty($hidden_job_id)) {

        } else {
            $newJobId = $this->getJobId();
            $objJob = new Job();
            $objJob->job_id = $newJobId;
            $objJob->company_id = $request->get('job_company_id');
            $objJob->job_title = $request->get('job_title');
            $objJob->address_1 = $request->get('address_1');
            $objJob->address_2 = $request->get('address_2');
            $objJob->city = $request->get('city');
            $objJob->state = $request->get('state');
            $objJob->zipcode = $request->get('zipcode');
            $objJob->apartment_number = $request->get('apartment_no');
            $objJob->super_name = $request->get('job_super_name');
            $objJob->super_phone_number = $request->get('super_phone_number');
            $objJob->contractor_name = $request->get('job_contractor_name');
            $objJob->contractor_phone_number = $request->get('contractor_phone_number');
            $objJob->contractor_email = $request->get('contractor_email');
            $objJob->working_employee_id = $working_employees;
            $objJob->company_clients_id = $comapny_clients;

            $objJob->plumbing_installation_date = date('Y-m-d', strtotime($request->get('plumbing_installation_date')));
            $objJob->delivery_datetime = date('Y-m-d H:i:s', strtotime($request->get('delivery_date') . ' ' . $request->get('delivery_time')));

            $objJob->job_status_id = 1;

            $objJob->is_select_installation = $request->get('installation_select');
            $objJob->installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('installation_date') . ' ' . $request->get('installation_time')));
            $objJob->installation_employee_id = $installation_employees;

            $objJob->is_select_stone_installation = $request->get('stone_installation_select');
            $objJob->stone_installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' ' . $request->get('stone_installation_time')));
            $objJob->stone_installation_employee_id = $stone_installation_employees;

            $objJob->is_active = $request->get('job_status');
            $objJob->is_deleted = 0;
            $objJob->start_date = date('Y-m-d', strtotime($request->get('job_start_date')));
            $objJob->end_date = date('Y-m-d', strtotime($request->get('job_end_date')));
            $objJob->created_at = date('Y-m-d H:i:s');
            $objJob->save();
        }

    }

    public function destroy($job_id)
    {
        Job::where('job_id', $job_id)->update(['is_deleted' => 1]);
        Session::flash('successMessage', 'Job has been removed Successfully');
        return redirect()->route('activejobs');
    }

    public function deactivateJob($job_id)
    {
        Job::where('job_id', $job_id)->update(['is_active' => 0]);
        Session::flash('successMessage', 'Job has been deactivated Successfully');
        return redirect()->route('activejobs');
    }

    public function getJobId()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 2; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $job_id = $randomString . mt_rand(100000, 999999);
        $check = Job::where('job_id', $job_id)->first();
        if (empty($check)) {
            return $job_id;
        } else {
            $this->getJobId();
        }
    }
}
