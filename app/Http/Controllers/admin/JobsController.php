<?php
namespace App\Http\Controllers\admin;

date_default_timezone_set('UTC');
use App\Admin;
use App\AuditTrail;
use App\Company;
use App\Http\Controllers\Controller;
use App\Job;
use DB;
use Illuminate\Http\Request;
use Session;
use App\JobType;
class JobsController extends Controller
{
    public function index()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,job_status_id,start_date,end_date,job_notes')->where('is_active', 1)->where('is_deleted', 0)->get();

        $getJobType = JobType::selectRaw('job_status_name,job_status_id')->get();
        return view('admin.jobs')->with('jobDetails', $getJobDetails)->with('jobTypeDetails',$getJobType);
    }

    public function showDeactivated()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,job_status_id,start_date,end_date')->where('is_active', 0)->where('is_deleted', 0)->get();

        $getJobType = JobType::selectRaw('job_status_name,job_status_id')->get();
        return view('admin.deactivatedjobs')->with('jobDetails', $getJobDetails)->with('jobTypeDetails',$getJobType);
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

        $getJobDetails->installation_date = date('m/d/Y', strtotime($getJobDetails->installation_datetime));
        $getJobDetails->installation_time = date('h:iA', strtotime($getJobDetails->installation_datetime));

        $getJobDetails->stone_installation_date = date('m/d/Y', strtotime($getJobDetails->stone_installation_datetime));
        $getJobDetails->stone_installation_time = date('h:iA', strtotime($getJobDetails->stone_installation_datetime));

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
        $working_employees = implode(',', $request->get('working_employee_id'));
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
                    $finalArray[] = array (
                        'job_id' => $hidden_job_id,
                        'field_name' => $key,
                        'old_value' => $oldValueArray[$key],
                        'new_value' => $newValueArray[$key],
                        'employee_id' => Session::get('employee_id'),
                        'name' => Session::get('name'),
                        'login_type_id' => Session::get('login_type_id')
                    );
                }
            }
            if(!empty($finalArray)){
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
        Job::where('job_id', $job_id)->update(['is_active' => 0]);
        Session::flash('successMessage', 'Job has been deactivated Successfully');
        return redirect()->route('activejobs');
    }

    public function storeJobNote(Request $request)
    {
        $hidden_job_id = $request->get('hidden_jobId');
        $job_noteDesc = $request->get('job_noteDesc');
        Job::where('job_id', $hidden_job_id)->update(['job_notes' => $job_noteDesc]);
        $response['key'] = 1;
        echo json_encode($response);
    }

    public function viewJobDetails()
    {
        $getJobDetails = DB::select('SELECT jb.created_at,jb.job_id,jb.job_notes,jt.job_status_name,cmp.name FROM jobs AS jb JOIN companies AS cmp ON cmp.company_id = jb.company_id JOIN job_types AS jt ON jt.job_status_id = jb.job_status_id WHERE jb.job_status_id IN ("2","5","6","7")');
        return view('admin.jobdetailsview')->with('jobDetails', $getJobDetails);
    }

    public function showAuditTrail(Request $request)
    {
        $job_id = $request->get('job_id');
        $html = '';
        $auditList = DB::select("SELECT * FROM audit_trail WHERE job_id = '{$job_id}'");
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
            foreach($auditList as $audit)
            {
                $html .='<tr>
                    <td>'.$audit->field_name.'</td>';
                    if(empty($audit->old_value)) {
                        $html .='<td>--</td>';
                    }else {
                        $html .='<td>'.$audit->old_value.' </td>';
                    }

                    if(empty($audit->new_value)) {
                        $html .='<td>--</td>';
                    }else { 
                        $html .='<td>'.$audit->new_value.'</td>';
                    }
                    
                $html .='<td>'.date("m/d/Y", strtotime($audit->created_at)).'</td>
                    <td>'.$audit->name.'</td>
                </tr>';
            }
            $html .= '</tbody></table>';
            $response['audit_data'] = $html;
            $response['key'] = 1;
        return json_encode($response);
    }

    public function changeJobStatus(Request $request) {
        $jobId = $request->get('jobId');
        $jobStatusId = $request->get('jobStatusId');
        $checkJob = $request->get('checkJob');

        if($jobStatusId == 8) {
            $jobUpdate = Job::where('job_id', $jobId)->update(['job_status_id' => $jobStatusId,'is_active' => 0]);
            $response['key'] = 1;
            if($checkJob == 1){
                Session::put('successMessage', 'Job Status has been Changed Successfully');
            }
        }else {
            $jobUpdate = Job::where('job_id', $jobId)->update(['job_status_id' => $jobStatusId,'is_active' => 1]);
            $response['key'] = 2;
            
            if($checkJob == 2){
                Session::put('successMessage', 'Job Status has been Changed Successfully');
            }
        }
        echo json_encode($response);
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