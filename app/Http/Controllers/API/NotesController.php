<?php

namespace App\Http\Controllers\API;
date_default_timezone_set('UTC');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Admin;
use App\Job;
use App\JobNote;
use Validator;

class NotesController extends Controller
{
    public function index(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'login_type_id' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
            }else {
                $user_id = $request->get('user_id');
                $login_type = $request->get('login_type_id');
                $jobId = array();
                /*Admin*/
                switch ($login_type) {
                    /* Admin */
                    case '1':
                        $getJobsNotesDetail = $this->getAllJobNotesDetails();
                        break;
                    /* Designer */
                    case '2':
                        $getJobId = DB::select("SELECT job_id FROM jobs WHERE ((working_employee_id LIKE '%{$user_id}%' AND job_status_id = 3) OR (service_employee_id LIKE '%{$user_id}%' AND job_status_id = 8))  AND is_deleted = 0 AND is_active = 1 ");
                        foreach($getJobId as $id) {
                            $jobId[] = $id->job_id;
                        }
                        $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                        break;
                    /* Measurer */
                    // case '3':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE ((working_employee_id LIKE '%{$user_id}%' AND job_status_id = 2) OR (service_employee_id LIKE '%{$user_id}%' AND job_status_id = 8))  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                    //     break;
                    // /* Delivery */
                    // case '4':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE ((working_employee_id LIKE '%{$user_id}%' AND (job_status_id = 5 OR job_status_id = 10)) OR (service_employee_id LIKE '%{$user_id}%' AND job_status_id = 8))  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                    //     break;
                    // /* Installer */
                    // case '5':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE ((working_employee_id LIKE '%{$user_id}%' AND (job_status_id = 6 OR job_status_id = 11)) OR (service_employee_id LIKE '%{$user_id}%' AND job_status_id = 8))  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                    //     break;
                    // /* Stone */
                    // case '6':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE ((working_employee_id LIKE '%{$user_id}%' AND (job_status_id = 7 OR job_status_id = 12)) OR (service_employee_id LIKE '%{$user_id}%' AND job_status_id = 8))  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                    //     break;
                    // /* Service */
                    // case '7':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE ((working_employee_id LIKE '%{$user_id}%' AND job_status_id = 8) OR service_employee_id LIKE '%{$user_id}%')  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                    //     break;
                    // /* Inspector */
                    // case '8':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE (working_employee_id LIKE '%{$user_id}%' OR service_employee_id LIKE '%{$user_id}%')  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                    //     break;
                    /* Client */
                    case '9':
                        $getJobId = DB::select("SELECT job_id FROM jobs WHERE company_clients_id LIKE '%{$user_id}%' AND is_deleted = 0 AND is_active = 1 ");
                        foreach($getJobId as $id) {
                            $jobId[] = $id->job_id;
                        }
                        $getJobsNotesDetail = $this->getAllJobNotesDetails($jobId);
                        break;
                    /* Sales */
                    // case '10':
                    //     $getJobId = DB::select("SELECT job_id FROM jobs WHERE (working_employee_id LIKE '%{$user_id}%' OR service_employee_id LIKE '%{$user_id}%')  AND is_deleted = 0 AND is_active = 1 ");
                    //     foreach($getJobId as $id) {
                    //         $jobId[] = $id->job_id;
                    //     }
                    //     break;
                }
                return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => 'Get detail successfully', 'response_data' => $getJobsNotesDetail]);
            }

        }catch (\Exception $e) {}
    }

    public function getAllJobNotesDetails($job_id = '') {
        if($job_id == '') {
            $jobNotesList = DB::table('job_notes as jn')
                ->selectRaw('jn.id as notes_id, jn.job_note, jn.name, DATE_FORMAT(jn.created_at, "%m/%d/%Y") as created_at, j.job_id, j.job_title')
                ->join('jobs as j', 'j.job_id', 'jn.job_id')
                ->where('jn.is_deleted', 0)
                ->orderBy('jn.created_at', 'asc')->get();
        }else {
            $jobNotesList = DB::table('job_notes as jn')
                ->selectRaw('jn.id as notes_id, jn.job_note, jn.name, DATE_FORMAT(jn.created_at, "%m/%d/%Y") as created_at, j.job_id, j.job_title')
                ->join('jobs as j', 'j.job_id', 'jn.job_id')
                ->where('jn.is_deleted', 0)
                ->whereIn('jn.job_id', $job_id)
                ->orderBy('jn.created_at', 'asc')->get();
        }
        return $jobNotesList;
    }
}
