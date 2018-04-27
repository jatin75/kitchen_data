<?php
namespace App\Http\Controllers\admin;
date_default_timezone_set('UTC');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use URL;
use Session;
use Mail;
use Validator;
use App\JobType;

class ReportsController extends Controller
{
    public function index()
    {
    	return view('admin.reports')->with('jobStatusList',JobType::all());
    }

    public function downloadJobExcel(Request $request)
    {
    	$status_id = $requets->get('status_id');

    	$getJobDetails = DB::select("SELECT jb.job_id,jb.client_id,jb.job_title,jb.address_1,jb.address_2,jb.city,jb.state,jb.zipcode,jb.apartment_number,jb.super_name,jb.super_phone_number,jb.contractor_name,jb.contractor_phone_number,jb.contractor_email,jb.working_employee_id,jb.job_client_id,jb.plumbing_installation_date,jb.delivery_datetime,jb.installation_datetime,jb.installation_employee_id,jb.stone_installation_datetime,jb.stone_installation_employee_id,jb.start_date,jb.end_date,jbt.job_status_name
    		FROM jobs AS jb
    		JOIN job_types AS jbt ON jbt.job_status_id = jb.job_status_id
    		WHERE jb.job_status_id = '{$status_id}'");

    	/*$path = public_path('/admin/csv/prospect.csv');
		return response()->download($path,'Kitchen_Jobss_'.date('Y_m_d').'.xls');*/
    }
}
