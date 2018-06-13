<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Job;
use App\JobNote;
use App\Admin;
use Mail;

class JobsController extends Controller
{
	/*Change job status*/
	public function changeJobStatus(Request $request) {
		try {
			$validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'job_id' => 'required',
                'user_login_type' => 'required',
                'user_name' => 'required',
                'job_status' => 'required',
                'job_pics' => 'required',
                'job_notes' => 'required',
            ]);
            if($validator->fails()) {
            	$messages = $validator->errors()->all();
            	$msg = $messages[0];
            	return response()->json(['success_code' => 200,'response_code' =>1, 'response_message'=> $msg]);
            }

        	$user_id = $request->get('user_id');
        	$user_name = $request->get('user_name');
			$job_id = $request->get('job_id');
			$user_login_type = $request->get('user_login_type');
			$job_status = $request->get('job_status');
			$job_pics = $request->file('job_pics');
			$job_notes = $request->get('job_notes');

			switch ($user_login_type) {
				case 2:
					switch ($job_status) {
						case 1:
							Job::where('job_id',$job_id)->update(['job_status_id'=> 3]);

							$ObjJobNote = new JobNote();
							$ObjJobNote->job_id = $job_id;
							$ObjJobNote->name = $user_name;
							$ObjJobNote->employee_id = $user_id;
							$ObjJobNote->job_note = $job_notes;
							$ObjJobNote->login_type_id = $user_login_type;
							$ObjJobNote->created_at = date('Y-m-d H:i:s');
            				$ObjJobNote->save();

            				$success['user_id'] = $user_id;

            				/*send Mail*/
					        $getDetail = Job::where('job_id',$job_id)->where('is_deleted',0)->first();
					        $working_employee_ids = explode(',', $getDetail->working_employee_id);
					        $this->sendMailDesign($working_employee_ids, $getDetail->job_title);

            				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully", 'response_data' => $success]);
						break;
						default:
							return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid job status. Please try again."]);
						break;
					}
				break;
				default:
					return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid user. Please try again."]);
				break;
			}
		} catch (\Exception $e) {}
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
}
