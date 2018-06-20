<?php

namespace App\Http\Controllers\admin;
date_default_timezone_set('UTC');
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminHomeController;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use PushNotification;
use FCM;
use App\Job;
use App\JobNote;
use App\JobType;
use App\Admin;
use App\AuditTrail;
use App\Company;
use DB;
use Session;
use Mail;

class JobsController extends Controller
{
    public function index()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,job_status_id,start_date,end_date')->where('is_active', 1)->where('is_deleted', 0)->orderBy('created_at','DESC')->get();
        $stoneEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 6");
        $installEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 5");

        $getJobType = JobType::selectRaw('job_status_name,job_status_id')->get();
        return view('admin.jobs')->with('jobDetails', $getJobDetails)->with('jobTypeDetails', $getJobType)->with('stoneEmployeeList', $stoneEmployeeList)->with('installEmployeeList', $installEmployeeList);
    }

    public function showDeactivated()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,job_status_id,start_date,end_date')->where('is_active', 0)->where('is_deleted', 0)->orderBy('updated_at','DESC')->get();

        $stoneEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 6");
        $installEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 5");

        $getJobType = JobType::selectRaw('job_status_name,job_status_id')->get();
        return view('admin.deactivatedjobs')->with('jobDetails', $getJobDetails)->with('jobTypeDetails', $getJobType)->with('stoneEmployeeList', $stoneEmployeeList)->with('installEmployeeList', $installEmployeeList);
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
        $getJobDetails = Job::where('job_id', $job_id)->first();
        $comapnyList = Company::selectRaw('company_id,name')->where('is_deleted', 0)->get();
        $employeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0");
        $stoneEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 6");
        $installEmployeeList = DB::select("SELECT id,CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 5");
        $getCompanyClients = DB::select("SELECT CONCAT(au.first_name,' ',au.last_name) AS client_name,au.id FROM clients AS cl JOIN admin_users AS au ON au.id = cl.client_id WHERE cl.company_id = '{$getJobDetails->company_id}'");

        $getJobDetails->start_date = date('m/d/Y', strtotime($getJobDetails->start_date));
        $getJobDetails->end_date = date('m/d/Y', strtotime($getJobDetails->end_date));
        $getJobDetails->plumbing_installation_date = date('m/d/Y', strtotime($getJobDetails->plumbing_installation_date));
        $getJobDetails->delivery_date = date('m/d/Y', strtotime($getJobDetails->delivery_datetime));
        $getJobDetails->delivery_time = date('h:iA', strtotime($getJobDetails->delivery_datetime));
        if(empty($getJobDetails->installation_datetime))
        {
            $getJobDetails->installation_date = null;
            $getJobDetails->installation_time = null;
        }
        else
        {
            $getJobDetails->installation_date = date('m/d/Y', strtotime($getJobDetails->installation_datetime));
            $getJobDetails->installation_time = date('h:iA', strtotime($getJobDetails->installation_datetime));
        }

        if(empty($getJobDetails->stone_installation_datetime))
        {
            $getJobDetails->stone_installation_date = null;
            $getJobDetails->stone_installation_time = null;
        }
        else
        {
            $getJobDetails->stone_installation_date = date('m/d/Y', strtotime($getJobDetails->stone_installation_datetime));
            $getJobDetails->stone_installation_time = date('h:iA', strtotime($getJobDetails->stone_installation_datetime));
        }

        if (!empty($getJobDetails->company_clients_id)) {
            $getJobDetails->company_clients_id = explode(",", $getJobDetails->company_clients_id);
        }
        if (!empty($getJobDetails->working_employee_id)) {
            $getJobDetails->working_employee_id = explode(",", $getJobDetails->working_employee_id);
        }
        if (!empty($getJobDetails->installation_employee_id)) {
            $getJobDetails->installation_employee_id = explode(",", $getJobDetails->installation_employee_id);
        }
        if (!empty($getJobDetails->stone_installation_employee_id)) {
            $getJobDetails->stone_installation_employee_id = explode(",", $getJobDetails->stone_installation_employee_id);
        }

        return view('admin.addjob')->with('jobDetails', $getJobDetails)->with('comapnyList', $comapnyList)->with('employeeList', $employeeList)->with('stoneEmployeeList', $stoneEmployeeList)->with('installEmployeeList', $installEmployeeList)->with('companyClientList', $getCompanyClients);
    }

    public function store(Request $request)
    {
        $hidden_job_id = $request->get('hidden_job_id');
        $working_employee_ids = $request->get('working_employee_id');
        $working_employees = implode(',', $working_employee_ids);
        $comapny_clients = implode(',', $request->get('comapny_clients_id'));
        $is_installation = $request->get('installation_select');
        $is_stone_installation = $request->get('stone_installation_select');
        if (!empty($hidden_job_id)) {
            $objJob = Job::where('job_id', $hidden_job_id)->first();
            /*Audit Trail start*/
            $oldValueArray = [];
            $newValueArray = [];
            $oldValueArray[] = $objJob->toArray();
            $oldValueArray = call_user_func_array('array_merge', $oldValueArray);
            /*Audit Trail end*/
            $objJob->company_id = $request->get('job_company_id');
            $objJob->job_title = $request->get('job_title');
            $objJob->address_1 = $request->get('address_1');
            $objJob->address_2 = $request->get('address_2');
            $objJob->city = $request->get('city');
            $objJob->state = $request->get('state');
            $objJob->zipcode = $request->get('zipcode');
            $objJob->apartment_number = $request->get('apartment_no');
            $objJob->super_name = $request->get('job_super_name');
            $objJob->super_phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('super_phone_number'));
            $objJob->contractor_name = $request->get('job_contractor_name');
            $objJob->contractor_phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('contractor_phone_number'));
            $objJob->contractor_email = $request->get('contractor_email');
            $objJob->working_employee_id = $working_employees;
            $objJob->company_clients_id = $comapny_clients;

            $objJob->plumbing_installation_date = date('Y-m-d', strtotime($request->get('plumbing_installation_date')));
            $objJob->delivery_datetime = date('Y-m-d H:i:s', strtotime($request->get('delivery_date') . ' ' . $request->get('delivery_time')));

            $objJob->is_select_installation = $is_installation;
            if ($is_installation == 1) {
                $objJob->installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('installation_date') . ' ' . $request->get('installation_time')));
                $objJob->installation_employee_id = implode(',', $request->get('installation_employees_id'));
            } else {
                $objJob->installation_datetime = null;
                $objJob->installation_employee_id = null;
            }

            $objJob->is_select_stone_installation = $is_stone_installation;
            if ($is_stone_installation == 1) {
                $objJob->stone_installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' ' . $request->get('stone_installation_time')));
                $objJob->stone_installation_employee_id = implode(',', $request->get('stone_installation_employees_id'));
            } else {
                $objJob->stone_installation_datetime = null;
                $objJob->stone_installation_employee_id = null;
            }

            $objJob->is_active = $request->get('job_status');
            $objJob->start_date = date('Y-m-d', strtotime($request->get('job_start_date')));
            $objJob->end_date = date('Y-m-d', strtotime($request->get('job_end_date')));

            /*Audit Trail start*/
            $newValueArray[] = $objJob->toArray();
            $newValueArray = call_user_func_array('array_merge', $newValueArray);
            foreach ($oldValueArray as $key => $old) {
                if ($newValueArray[$key] != $oldValueArray[$key]) {
                    $finalArray[] = array(
                        'job_id' => $hidden_job_id,
                        'field_name' => $key,
                        'old_value' => $oldValueArray[$key],
                        'new_value' => $newValueArray[$key],
                        'employee_id' => Session::get('employee_id'),
                        'name' => Session::get('name'),
                        'login_type_id' => Session::get('login_type_id'),
                    );
                }
            }
            if (!empty($finalArray)) {
                AuditTrail::insert($finalArray);
            }
            /*Audit Trail end*/
            $objJob->save();
            $response['key'] = 2;
            return json_encode($response);
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
            $objJob->super_phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('super_phone_number'));
            $objJob->contractor_name = $request->get('job_contractor_name');
            $objJob->contractor_phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('contractor_phone_number'));
            $objJob->contractor_email = $request->get('contractor_email');
            $objJob->working_employee_id = $working_employees;
            $objJob->company_clients_id = $comapny_clients;

            $objJob->plumbing_installation_date = date('Y-m-d', strtotime($request->get('plumbing_installation_date')));
            $objJob->delivery_datetime = date('Y-m-d H:i:s', strtotime($request->get('delivery_date') . ' ' . $request->get('delivery_time')));

            $objJob->job_status_id = 1;

            $objJob->is_select_installation = $is_installation;
            if ($is_installation == 1) {
                $objJob->installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('installation_date') . ' ' . $request->get('installation_time')));
                $objJob->installation_employee_id = implode(',', $request->get('installation_employees_id'));
            } else {
                $objJob->installation_datetime = null;
                $objJob->installation_employee_id = null;
            }

            $objJob->is_select_stone_installation = $is_stone_installation;
            if ($is_stone_installation == 1) {
                $objJob->stone_installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' ' . $request->get('stone_installation_time')));
                $objJob->stone_installation_employee_id = implode(',', $request->get('stone_installation_employees_id'));
            } else {
                $objJob->stone_installation_datetime = null;
                $objJob->stone_installation_employee_id = null;
            }

            $objJob->is_active = $request->get('job_status');
            $objJob->is_deleted = 0;
            $objJob->start_date = date('Y-m-d', strtotime($request->get('job_start_date')));
            $objJob->end_date = date('Y-m-d', strtotime($request->get('job_end_date')));
            $objJob->created_at = date('Y-m-d H:i:s');
            $objJob->save();
            Session::put('successMessage', 'Job has been added Successfully');
            $response['key'] = 1;

            /*send Mail*/
            $this->sendMailNew($working_employee_ids,$request->get('job_title'));
            return json_encode($response);
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
        Job::where('job_id', $job_id)->update(['is_active' => 0,'job_status_id'=>8]);
        Session::flash('successMessage', 'Job has been deactivated Successfully');
        return redirect()->route('activejobs');
    }

    public function storeJobNote(Request $request)
    {
        $hidden_job_id = $request->get('hidden_job_id');
        $job_note_desc = $request->get('job_note_desc');
        $job_note_status = $request->get('job_note_status');

        if($job_note_status == 1)
        {
            $ObjJobNote = new JobNote();
            $ObjJobNote->job_id = $hidden_job_id;
            $ObjJobNote->employee_id = Session::get('employee_id');
            $ObjJobNote->name = Session::get('name');
            $ObjJobNote->job_note = $job_note_desc;
            $ObjJobNote->login_type_id = Session::get('login_type_id');
            $ObjJobNote->created_at = date('Y-m-d H:i:s');
            $ObjJobNote->save();
        }
        else
        {
            JobNote::where('id',$hidden_job_id)->update([
                'employee_id'=>Session::get('employee_id'),
                'name'=>Session::get('name'),
                'login_type_id'=>Session::get('login_type_id'),
                'job_note'=>$job_note_desc]);
        }
        $response['key'] = 1;
        echo json_encode($response);
    }

    public function viewJobDetails(Request $request)
    {
        $job_id = $request->get('job_id');
        $getJobDetails = DB::select("SELECT j.job_id,j.company_id,j.job_title,j.address_1,j.address_2,j.city,j.state,j.zipcode,j.apartment_number,j.super_name,j.super_phone_number,j.contractor_name,j.contractor_phone_number,j.contractor_email,j.working_employee_id,j.company_clients_id,j.plumbing_installation_date,j.delivery_datetime,j.job_status_id,j.is_select_installation,j.installation_datetime,j.installation_employee_id,j.is_select_stone_installation,j.stone_installation_datetime,j.stone_installation_employee_id,j.is_active,j.start_date,j.end_date,j.created_at,cmp.name AS company_name,jbt.job_status_name
            FROM jobs AS j
            JOIN companies AS cmp ON cmp.company_id = j.company_id
            JOIN job_types AS jbt ON jbt.job_status_id = j.job_status_id
            WHERE j.job_id = '{$job_id}'");
        if (sizeof($getJobDetails) > 0) {
            $getJobDetails = $getJobDetails[0];
            $getJobDetails->is_active = ($getJobDetails->is_active == 1) ? 'Active':'Inactive' ;
            $getJobDetails->super_phone_number = (!empty($getJobDetails->super_phone_number)) ? (new AdminHomeController)->formatPhoneNumber($getJobDetails->super_phone_number) : '--';
            $getJobDetails->contractor_phone_number = (!empty($getJobDetails->contractor_phone_number)) ? (new AdminHomeController)->formatPhoneNumber($getJobDetails->contractor_phone_number) : '--';

            $getJobDetails->start_date = date('m/d/Y', strtotime($getJobDetails->start_date));
            $getJobDetails->end_date = date('m/d/Y', strtotime($getJobDetails->end_date));
            $getJobDetails->plumbing_installation_date = date('m/d/Y', strtotime($getJobDetails->plumbing_installation_date));
            $getJobDetails->delivery_datetime = date('m/d/Y h:iA', strtotime($getJobDetails->delivery_datetime));

            $getJobDetails->installation_datetime = date('m/d/Y h:iA', strtotime($getJobDetails->installation_datetime));

            $getJobDetails->stone_installation_datetime = date('m/d/Y h:iA', strtotime($getJobDetails->stone_installation_datetime));
            if (!empty($getJobDetails->working_employee_id)) {
                $getJobDetails->working_employee_name = $this->commonViewJobDetails($getJobDetails->working_employee_id);
            }
            if (!empty($getJobDetails->company_clients_id)) {
                $getJobDetails->company_clients_name  = $this->commonViewJobDetails($getJobDetails->company_clients_id);
            }
            if (!empty($getJobDetails->installation_employee_id)) {
                $getJobDetails->installation_employee_name  = $this->commonViewJobDetails($getJobDetails->installation_employee_id);
            }
            if (!empty($getJobDetails->stone_installation_employee_id)) {
                $getJobDetails->stone_installation_employee_name  = $this->commonViewJobDetails($getJobDetails->stone_installation_employee_id);
            }
        }
        $getJobNotes = DB::select("SELECT id,name,job_note,updated_at FROM job_notes WHERE is_deleted = 0 AND job_id = '{$job_id}'");
        $html = '';
        if (sizeof($getJobNotes) > 0)
        {
            foreach($getJobNotes as $single_note)
            {
                $html .= '<div class="row" id="row_'. $single_note->id .'">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" class="word-wrap">
                <span id="note">'. $single_note->job_note .'</span>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <span id="updated_by">'. $single_note->name .'</span>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <span id="updated_date">'. date('m/d/Y', strtotime($single_note->updated_at)) .'</span>
                </div>
                <div class="col-xs-2">
                <a data-toggle="tooltip" data-placement="top" class="edit-note" title="Edit" data-id ="'. $single_note->id .'">
                <i class="ti-pencil-alt"></i>
                </a>
                </div>
                <div class="col-xs-2">
                <a data-toggle="tooltip" data-placement="top" class="delete-note" title="Remove" data-id ="'. $single_note->id .'">
                <i class="ti-trash"></i>
                </a>
                </div>
                </div>';
            }
        }
        $response['employee_detail'] = $getJobDetails;
        $response['job_notes_detail'] = $html;
        $response['key'] = 1;
        return json_encode($response);
    }

    public function editNote(Request $request)
    {
        $job_id = $request->get('job_id');
        $getJobNote = DB::select("SELECT id,name,job_note FROM job_notes WHERE is_deleted = 0 AND id = '{$job_id}'");
        if (sizeof($getJobNote) > 0)
        {
            $getJobNote = $getJobNote[0];
            $response['key'] = 1;
            $response['job_note_detail'] = $getJobNote;
            return json_encode($response);
        }
    }

    public function destroyNote(Request $request)
    {
        $job_id = $request->get('job_id');
        JobNote::where('id',$job_id)->update(['is_deleted'=>1]);
        $response['key'] = 1;
        return json_encode($response);
    }

    public function showAuditTrail(Request $request)
    {
        $job_id = $request->get('job_id');
        $html = '';
        $auditList = AuditTrail::where('job_id',$job_id)->get();
        $html .= '<table id="auditList" class="display nowrap" cellspacing="0" width="100%">
        <thead>
        <tr>
        <th>Name Of Field</th>
        <th>Old Value</th>
        <th>New Value</th>
        <th>Date Of Edit</th>
        <th>User</th>
        </tr>
        </thead><tbody>';
        foreach ($auditList as $audit) {
            $html .= '<tr>
            <td>' . $audit->field_name . '</td>';
            if (empty($audit->old_value)) {
                $html .= '<td>--</td>'; 
            }elseif ($audit->field_name == 'delivery_datetime' || $audit->field_name == 'installation_datetime' || $audit->field_name == 'stone_installation_datetime') {
                $html .= '<td>' . date("m/d/Y h:iA", strtotime($audit->old_value)) . '</td>';
            }elseif ($audit->field_name == 'super_phone_number' || $audit->field_name == 'contractor_phone_number') {
                $html .= '<td>' . substr_replace(substr_replace(substr_replace($audit->old_value, '(', 0,0), ') ', 4,0), ' - ', 9,0) . '</td>';
            }elseif ($audit->field_name == 'job_status_id') {
                $status = JobType::selectRaw('job_status_name')->where('job_status_id',$audit->old_value)->first();
                $html .= '<td>' . $status->job_status_name . ' </td>';
            }elseif ($audit->field_name == 'plumbing_installation_date' || $audit->field_name == 'start_date' || $audit->field_name == 'end_date') {
                $html .= '<td>' . date("m/d/Y", strtotime($audit->old_value)) . '</td>';
            }elseif ($audit->field_name == 'is_select_installation' || $audit->field_name == 'is_select_stone_installation') {
                $is_select = $audit->old_value == 1 ? "Yes" : "No";
                $html .= '<td>' . $is_select . '</td>';
            }elseif ($audit->field_name == 'is_active') {
                $is_active = $audit->old_value == 1 ? "Active" : "Inactive";
                $html .= '<td>' . $is_active . '</td>';
            }
            else {
                $html .= '<td>' . $audit->old_value . ' </td>';
            }

            if (empty($audit->new_value)) {
                $html .= '<td>--</td>';
            }elseif ($audit->field_name == 'delivery_datetime' || $audit->field_name == 'installation_datetime' || $audit->field_name == 'stone_installation_datetime') {
                $html .= '<td>' . date("m/d/Y h:iA", strtotime($audit->new_value)) . '</td>';
            }elseif ($audit->field_name == 'super_phone_number' || $audit->field_name == 'contractor_phone_number') {
                $html .= '<td>' . substr_replace(substr_replace(substr_replace($audit->new_value, '(', 0,0), ') ', 4,0), ' - ', 9,0) . '</td>';
            }elseif ($audit->field_name == 'job_status_id') {
                $status = JobType::selectRaw('job_status_name')->where('job_status_id',$audit->new_value)->first();
                $html .= '<td>' . $status->job_status_name . ' </td>';
            }elseif ($audit->field_name == 'plumbing_installation_date' || $audit->field_name == 'start_date' || $audit->field_name == 'end_date') {
                $html .= '<td>' . date("m/d/Y", strtotime($audit->new_value)) . '</td>';
            }elseif ($audit->field_name == 'is_select_installation' || $audit->field_name == 'is_select_stone_installation') {
                $is_select = $audit->new_value == 1 ? "Yes" : "No";
                $html .= '<td>' . $is_select . '</td>';
            }elseif ($audit->field_name == 'is_active') {
                $is_active = $audit->new_value == 1 ? "Active" : "Inactive";
                $html .= '<td>' . $is_active . '</td>';
            }
            else {
                $html .= '<td>' . $audit->new_value . '</td>';
            }

            $html .= '<td>' . date("m/d/Y", strtotime($audit->created_at)) . '</td>
            <td>' . $audit->name . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';
        $response['audit_data'] = $html;
        $response['key'] = 1;
        return json_encode($response);
    }

    public function editJobDateTimeModel(Request $request) {
        $jobId = $request->get('jobId');
        $getJobDetails = Job::selectRaw('delivery_datetime,installation_datetime,installation_employee_id,stone_installation_datetime,stone_installation_employee_id,job_status_id')->where('job_id',$jobId)->first();
        $getJobDetails->delivery_date = date('m/d/Y', strtotime($getJobDetails->delivery_datetime));
        $getJobDetails->delivery_time = date('h:iA', strtotime($getJobDetails->delivery_datetime));
        if(empty($getJobDetails->installation_datetime)) {
            $getJobDetails->installation_date = null;
            $getJobDetails->installation_time = null;
        }else {
            $getJobDetails->installation_date = date('m/d/Y', strtotime($getJobDetails->installation_datetime));
            $getJobDetails->installation_time = date('h:iA', strtotime($getJobDetails->installation_datetime));
        }

        if(empty($getJobDetails->stone_installation_datetime)) {
            $getJobDetails->stone_installation_date = null;
            $getJobDetails->stone_installation_time = null;
        }else {
            $getJobDetails->stone_installation_date = date('m/d/Y', strtotime($getJobDetails->stone_installation_datetime));
            $getJobDetails->stone_installation_time = date('h:iA', strtotime($getJobDetails->stone_installation_datetime));
        }

        if (!empty($getJobDetails->installation_employee_id)) {
            $getJobDetails->installation_employee_id = explode(",", $getJobDetails->installation_employee_id);
        }
        if (!empty($getJobDetails->stone_installation_employee_id)) {
            $getJobDetails->stone_installation_employee_id = explode(",", $getJobDetails->stone_installation_employee_id);
        }

        $response['job_detail'] = $getJobDetails;
        $response['key'] = 1;
        return json_encode($response);
    }

    public function changeJobStatus(Request $request)
    {
        $jobId = $request->get('jobId');
        $jobStatusId = $request->get('jobStatusId');

        $is_active = ($jobStatusId == 8) ? 0 : 1;
        $key1 = ($jobStatusId == 8) ? 1 : 2;
        $oldValueArray = [];
        $newValueArray = [];

        if($jobStatusId == 5) {
            $delivery_datetime = date('Y-m-d H:i:s', strtotime($request->get('date') . ' ' . $request->get('time')));

            /*Audit Trail start*/
            $jobDetail = DB::select("SELECT job_status_id,is_active,delivery_datetime FROM jobs WHERE job_id = '{$jobId}'");
            $oldValueArray = json_decode(json_encode($jobDetail), true);
            $oldValueArray = call_user_func_array('array_merge', $oldValueArray);
            $newValueArray = array(
                'job_status_id' => $jobStatusId,
                'is_active' => $is_active,
                'delivery_datetime'=>$delivery_datetime
            );
            foreach ($oldValueArray as $key => $old) {
                if ($newValueArray[$key] != $oldValueArray[$key]) {
                    $finalArray[] = array(
                        'job_id' => $jobId,
                        'field_name' => $key,
                        'old_value' => $oldValueArray[$key],
                        'new_value' => $newValueArray[$key],
                        'employee_id' => Session::get('employee_id'),
                        'name' => Session::get('name'),
                        'login_type_id' => Session::get('login_type_id'),
                    );
                }
            }
            if (isset($finalArray) && !empty($finalArray)) {
                AuditTrail::insert($finalArray);
            }
            /*Audit Trail end*/

            $jobUpdate = Job::where('job_id', $jobId)->update(['job_status_id' => $jobStatusId, 'is_active' => $is_active,'delivery_datetime'=>$delivery_datetime]);

        }elseif($jobStatusId == 6) {
            $installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('date') . ' ' . $request->get('time')));
            $installation_employee_id = implode(',', $request->get('employee'));

            /*Audit Trail start*/
            $jobDetail = DB::select("SELECT job_status_id,is_active,installation_datetime,installation_employee_id,is_select_installation FROM jobs WHERE job_id = '{$jobId}'");
            $oldValueArray = json_decode(json_encode($jobDetail), true);
            $oldValueArray = call_user_func_array('array_merge', $oldValueArray);
            $newValueArray = array(
                'job_status_id' => $jobStatusId,
                'is_active' => $is_active,
                'installation_datetime'=>$installation_datetime,
                'installation_employee_id'=>$installation_employee_id,
                'is_select_installation'=>1
            );
            foreach ($oldValueArray as $key => $old) {
                if ($newValueArray[$key] != $oldValueArray[$key]) {
                    $finalArray[] = array(
                        'job_id' => $jobId,
                        'field_name' => $key,
                        'old_value' => $oldValueArray[$key],
                        'new_value' => $newValueArray[$key],
                        'employee_id' => Session::get('employee_id'),
                        'name' => Session::get('name'),
                        'login_type_id' => Session::get('login_type_id'),
                    );
                }
            }
            if (isset($finalArray) && !empty($finalArray)) {
                AuditTrail::insert($finalArray);
            }
            /*Audit Trail end*/
            $jobUpdate = Job::where('job_id', $jobId)->update(['job_status_id' => $jobStatusId, 'is_active' => $is_active,'installation_datetime'=>$installation_datetime,'installation_employee_id'=>$installation_employee_id,'is_select_installation'=>1]);

        }elseif($jobStatusId == 7) {
            $stoneInstallation_datetime = date('Y-m-d H:i:s', strtotime($request->get('date') . ' ' . $request->get('time')));
            $stoneInstallation_employee_id = implode(',', $request->get('employee'));

            /*Audit Trail start*/
            $jobDetail = DB::select("SELECT job_status_id,is_active,stone_installation_datetime,stone_installation_employee_id,is_select_stone_installation FROM jobs WHERE job_id = '{$jobId}'");
            $oldValueArray = json_decode(json_encode($jobDetail), true);
            $oldValueArray = call_user_func_array('array_merge', $oldValueArray);
            $newValueArray = array(
                'job_status_id' => $jobStatusId,
                'is_active' => $is_active,
                'stone_installation_datetime'=>$stoneInstallation_datetime,
                'stone_installation_employee_id'=>$stoneInstallation_employee_id,
                'is_select_stone_installation'=>1
            );
            foreach ($oldValueArray as $key => $old) {
                if ($newValueArray[$key] != $oldValueArray[$key]) {
                    $finalArray[] = array(
                        'job_id' => $jobId,
                        'field_name' => $key,
                        'old_value' => $oldValueArray[$key],
                        'new_value' => $newValueArray[$key],
                        'employee_id' => Session::get('employee_id'),
                        'name' => Session::get('name'),
                        'login_type_id' => Session::get('login_type_id'),
                    );
                }
            }
            if (isset($finalArray) && !empty($finalArray)) {
                AuditTrail::insert($finalArray);
            }
            /*Audit Trail end*/

            $jobUpdate = Job::where('job_id', $jobId)->update(['job_status_id' => $jobStatusId, 'is_active' => $is_active,'stone_installation_datetime'=>$stoneInstallation_datetime,'stone_installation_employee_id'=>$stoneInstallation_employee_id,'is_select_stone_installation'=>1]);

        }else {

            /*Audit Trail start*/
            $jobDetail = DB::select("SELECT job_status_id,is_active FROM jobs WHERE job_id = '{$jobId}'");
            $oldValueArray = json_decode(json_encode($jobDetail), true);
            $oldValueArray = call_user_func_array('array_merge', $oldValueArray);
            $newValueArray = array(
                'job_status_id' => $jobStatusId,
                'is_active' => $is_active
            );
            foreach ($oldValueArray as $key => $old) {
                if ($newValueArray[$key] != $oldValueArray[$key]) {
                    $finalArray[] = array(
                        'job_id' => $jobId,
                        'field_name' => $key,
                        'old_value' => $oldValueArray[$key],
                        'new_value' => $newValueArray[$key],
                        'employee_id' => Session::get('employee_id'),
                        'name' => Session::get('name'),
                        'login_type_id' => Session::get('login_type_id'),
                    );
                }
            }
            if (isset($finalArray) && !empty($finalArray)) {
                AuditTrail::insert($finalArray);
            }
            /*Audit Trail end*/

            $jobUpdate = Job::where('job_id', $jobId)->update(['job_status_id' => $jobStatusId, 'is_active' => $is_active]);
        }

        $response['key'] = $key1;

        /*send Mail*/
        $getDetail = Job::where('job_id',$jobId)->where('is_deleted',0)->first();
        $working_employee_ids = explode(',', $getDetail->working_employee_id);
        $company_client_ids = explode(',', $getDetail->company_clients_id);
        switch ($jobStatusId) {
            case 1:
            $this->sendMailNew($working_employee_ids, $getDetail->job_title);
            /*send notification as client */
            if(sizeof($company_client_ids) > 0)
            {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach ($company_client_ids as $client_id) {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id);
                    if(!empty($device_detail->device_token)) {
                        $messageBody = 'NEW '.$getDetail->job_title .'CREATED.';
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            break;
            case 2:
            $this->sendMailMeasuring($getDetail);
            /*send notification as measurer */
            if(sizeof($working_employee_ids) > 0)
            {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach($working_employee_ids as $id)
                {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$id)->where('login_type_id',3)->where('is_deleted',0)->first();
                    if(!empty($device_detail) && (!empty($device_detail->device_token))) {
                        $messageBody = 'NEW JOB TO MEASURE: '.$getDetail->job_title;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            break;
            case 3:
            $this->sendMailDesign($working_employee_ids, $getDetail->job_title);
            break;
            case 5:
            $this->sendMailDelivery($getDetail);
            $delivery_date = date('m/d/Y', strtotime($getDetail->delivery_datetime));
            /*send notification as delivery */
            if(sizeof($working_employee_ids) > 0) {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach($working_employee_ids as $id)
                {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$id)->where('login_type_id',4)->where('is_deleted',0)->first();
                    if(!empty($device_detail) && (!empty($device_detail->device_token))) {
                        $messageBody = $getDetail->job_title.' Scheduled for Deliver '.$delivery_date;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            if(sizeof($company_client_ids) > 0) {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach ($company_client_ids as $client_id) {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id);
                    if(!empty($device_detail->device_token)) {
                        $messageBody = $getDetail->job_title.' Scheduled for Deliver '.$delivery_date;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            break;
            case 6:
            $this->sendMailInstallation($getDetail->job_title,$getDetail->delivery_datetime,$getDetail->contractor_email);
            $installation_date = date('m/d/Y', strtotime($getDetail->installation_datetime));
            /*send notification as installer */
            if(sizeof($working_employee_ids) > 0) {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach($working_employee_ids as $id)
                {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$id)->where('login_type_id',5)->where('is_deleted',0)->first();
                    if(!empty($device_detail) && (!empty($device_detail->device_token))) {
                        $messageBody = $getDetail->job_title.' Scheduled for INSTALLATION '.$installation_date;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            if(sizeof($company_client_ids) > 0) {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach ($company_client_ids as $client_id) {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id);
                    if(!empty($device_detail->device_token)) {
                        $messageBody = $getDetail->job_title.' Scheduled for INSTALLATION '.$installation_date;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            break;
            case 7:
            $this->sendMailStoneInstallation($getDetail->job_title,$getDetail->delivery_datetime,$getDetail->contractor_email);
            $stone_installation_date = date('m/d/Y', strtotime($getDetail->stone_installation_datetime));
            /*send notification as stone installer */
            if(sizeof($working_employee_ids) > 0) {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach($working_employee_ids as $id)
                {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$id)->where('login_type_id',6)->where('is_deleted',0)->first();
                    if(!empty($device_detail) && (!empty($device_detail->device_token))) {
                        $messageBody = $getDetail->job_title.' Scheduled for STONE INSTALLATION '.$stone_installation_date;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }

            if(sizeof($company_client_ids) > 0) {
                $title = 'Change Job Status';
                $badge = '1';
                $sound = 'default';

                foreach ($company_client_ids as $client_id) {
                    $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id);
                    if(!empty($device_detail->device_token)) {
                        $messageBody = $getDetail->job_title.' Scheduled for STONE INSTALLATION '.$stone_installation_date;
                        $deviceid = $device_detail->device_token;
                        $device_type = $device_detail->device_type;
                        $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                    }
                }
            }
            break;
        }
        echo json_encode($response);
    }

    function commonViewJobDetails($ids)
    {
        $allEmployeeId = explode(",", $ids);
        $employeeNames = [];
        foreach($allEmployeeId as $employeeId)
        {
            $getEmployeeName = DB::select("SELECT CONCAT(first_name,' ',last_name) AS employee_name FROM admin_users WHERE id = '{$employeeId}'");
            if(sizeof($getEmployeeName) > 0)
            {
                $employeeNames[] = $getEmployeeName[0]->employee_name;
            }
        }
        if(sizeof($employeeNames) > 0)
        {
            return implode(", ", $employeeNames);
        }
    }

    /* New Status */
    function sendMailNew($working_employee_ids,$job_title)
    {
        $email_ids = [];
        foreach($working_employee_ids as $id)
        {
            $email_id = Admin::selectRaw('email')->where('id',$id)->where('login_type_id',1)->where('is_deleted',0)->first();
            if(!empty($email_id))
            {
                $email_ids[] = $email_id->email;
            }
        }
        if(sizeof($email_ids) > 0)
        {
            /*send Mail*/
            Mail::send('emails.AdminPanel_JobNew',array(
                'job_title' =>  $job_title,
            ), function($message)use($email_ids){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($email_ids)->subject('A&S KITCHEN | New Job Created');
            });
        }
        return;
    }

    /* Measuring Status */
    function sendMailMeasuring($job_Detail)
    {
        $working_employee_ids = explode(',', $job_Detail->working_employee_id);
        $job_title = $job_Detail->job_title;

        $delimiter = ','.' ';
        $job_address = $job_Detail->address_1.$delimiter;
        $job_address .= (!empty($job_Detail->apartment_number)) ? 'Apartment no: '.$job_Detail->apartment_number.$delimiter : '';
        $job_address .= (!empty($job_Detail->address_2)) ? $job_Detail->address_2.$delimiter : '';
        $job_address .= (!empty($job_Detail->city)) ? $job_Detail->city.$delimiter : '';
        $job_address .= (!empty($job_Detail->state)) ? $job_Detail->state.$delimiter : '';
        $job_address .= (!empty($job_Detail->zipcode)) ? $job_Detail->zipcode : '';
        $super_name = $job_Detail->super_name;

        $email_ids = [];
        foreach($working_employee_ids as $id)
        {
            $email_id = Admin::selectRaw('email')->where('id',$id)->where('login_type_id',3)->where('is_deleted',0)->first();
            if(!empty($email_id)) {
                $email_ids[] = $email_id->email;
            }
        }
        if(sizeof($email_ids) > 0)
        {
            /*send Mail*/
            Mail::send('emails.AdminPanel_JobMeasuring',array(
                'job_title' =>  $job_title,
                'job_address' =>  $job_address,
                'super_name' =>  $super_name,
                'is_admin' => 0,
            ), function($message)use($email_ids, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($email_ids)->subject('A&S KITCHEN | '.$job_title);
            });
        }
        else
        {
            foreach($working_employee_ids as $id)
            {
                $email_id = Admin::selectRaw('email')->where('id',$id)->where('login_type_id',1)->where('is_deleted',0)->first();
                if(!empty($email_id)) {
                    $email_ids[] = $email_id->email;
                }
            }
            if(sizeof($email_ids) > 0)
            {
                /*send Mail*/
                Mail::send('emails.AdminPanel_JobMeasuring',array(
                    'job_title' =>  $job_title,
                    'is_admin' => 1,
                ), function($message)use($email_ids, $job_title){
                    $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                    $message->bcc($email_ids)->subject('A&S KITCHEN | '.$job_title);
                });
            }
        }
        return;
    }

    /* Design Status */
    function sendMailDesign($working_employee_ids,$job_title)
    {
        $email_ids = [];
        foreach($working_employee_ids as $id)
        {
            $email_id = Admin::selectRaw('email')->where('id',$id)->where('login_type_id',3)->where('is_deleted',0)->first();
            if(!empty($email_id))
            {
                $email_ids[] = $email_id->email;
            }
        }
        if(sizeof($email_ids) > 0)
        {
            /*send Mail*/
            Mail::send('emails.AdminPanel_JobDesign',array(
                'job_title' =>  $job_title,
                ), function($message)use($email_ids, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($email_ids)->subject('A&S KITCHEN | '.$job_title);
            });
        }
        return;
    }

    /* Delivery Status */
    function sendMailDelivery($job_Detail)
    {
        $working_employee_ids = explode(',', $job_Detail->working_employee_id);
        $job_title = $job_Detail->job_title;
        $delivery_date = date('m/d/Y', strtotime($job_Detail->delivery_datetime));

        /*Contractor*/
        $contractor_email = $job_Detail->contractor_email;
        if(!empty($contractor_email))
        {
            /*send Mail*/
            Mail::send('emails.AdminPanel_JobDeliveryContractor',array(
                'job_title' =>  $job_title,
                'delivery_date' =>  $delivery_date,
                ), function($message)use($contractor_email, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($contractor_email)->subject('A&S KITCHEN | '.$job_title);
            });
        }

        /*Delivery Employee*/
        $email_ids = [];
        foreach($working_employee_ids as $id)
        {
            $email_id = Admin::selectRaw('email')->where('id',$id)->where('login_type_id',4)->where('is_deleted',0)->first();
            if(!empty($email_id))
            {
                $email_ids[] = $email_id->email;
            }
        }
        if(sizeof($email_ids) > 0)
        {
            $delimiter = ','.' ';
            $job_address = $job_Detail->address_1.$delimiter;
            $job_address .= (!empty($job_Detail->apartment_number)) ? 'Apartment no: '.$job_Detail->apartment_number.$delimiter : '';
            $job_address .= (!empty($job_Detail->address_2)) ? $job_Detail->address_2.$delimiter : '';
            $job_address .= (!empty($job_Detail->city)) ? $job_Detail->city.$delimiter : '';
            $job_address .= (!empty($job_Detail->state)) ? $job_Detail->state.$delimiter : '';
            $job_address .= (!empty($job_Detail->zipcode)) ? $job_Detail->zipcode : '';

            $super_name = $job_Detail->super_name;
            $super_contact_number = (new AdminHomeController)->formatPhoneNumber($job_Detail->super_phone_number);
            $contractor_name = $job_Detail->contractor_name;
            $contractor_contact_number = (new AdminHomeController)->formatPhoneNumber($job_Detail->contractor_phone_number);

            /*send Mail*/
            Mail::send('emails.AdminPanel_JobDeliveryEmployee',array(
                'job_title' =>  $job_title,
                'delivery_date' =>  $delivery_date,
                'job_address' =>  $job_address,
                'super_name' =>  $super_name,
                'super_contact_number' =>  $super_contact_number,
                'contractor_name' =>  $contractor_name,
                'contractor_contact_number' =>  $contractor_contact_number,
                ), function($message)use($email_ids, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($email_ids)->subject('A&S KITCHEN | '.$job_title);
            });
        }
        return;
    }

    /* Installation Status */
    function sendMailInstallation($job_title,$delivery_datetime,$contractor_email)
    {
        $delivery_date = date('m/d/Y', strtotime($delivery_datetime));
        /*Contractor*/
        if(!empty($contractor_email))
        {
            /*send Mail*/
            Mail::send('emails.AdminPanel_JobInstalling',array(
                'job_title' =>  $job_title,
                'delivery_date' =>  $delivery_date,
                ), function($message)use($contractor_email, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($contractor_email)->subject('A&S KITCHEN | '.$job_title);
            });
        }
        return;
    }

    /* Stone installation Status */
    function sendMailStoneInstallation($job_title,$delivery_datetime,$contractor_email)
    {
        $delivery_date = date('m/d/Y', strtotime($delivery_datetime));
        /*Contractor*/
        if(!empty($contractor_email))
        {
            /*send Mail*/
            Mail::send('emails.AdminPanel_JobStoneInstalling',array(
                'job_title' =>  $job_title,
                'delivery_date' =>  $delivery_date,
                ), function($message)use($contractor_email, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($contractor_email)->subject('A&S KITCHEN | '.$job_title);
            });
        }
        return;
    }

    /*pushNotification */
    public function pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound='dafault')
    {
        if(strtolower($device_type) == 'ios') {

            $message = PushNotification::message($messageBody,array(
                'title' => $title,
                'badge' => $badge,
                'sound' => $sound,
            ));
            $push = PushNotification::app('KITCHENIOS')->to($deviceid)->send($message);
        }
        elseif (strtolower($device_type) == 'android') {

            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60*20);

            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($messageBody)->setSound($sound)->setBadge($badge);

            $dataBuilder = new PayloadDataBuilder();

            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $downstreamResponse = FCM::sendTo($deviceid, $option, $notification, $data);
        }
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