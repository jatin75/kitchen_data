<?php

namespace App\Http\Controllers\API;

date_default_timezone_set('UTC');
use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use PushNotification;
use FCM;
use App\Job;
use App\JobNote;
use App\JobType;
use DB;
use Illuminate\Http\Request;
use Mail;
use Storage;
use Validator;


class JobsController extends Controller
{

    /**
     * Get User Job Details
     *
     * @return User Job Details
     */
    public function getUserJobDetails(Request $request)
    {
    	try {
    		$validator = Validator::make($request->all(), [
    			'user_id' => 'required',
    			'login_type_id' => 'required',
    		]);
    		if ($validator->fails()) {
    			$messages = $validator->errors()->all();
    			$msg = $messages[0];
    			return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
    		}
    		$user_id = $request->get('user_id');
    		$login_type_id = $request->get('login_type_id');
    		switch ($login_type_id) {
    			/* Admin */
    			case '1':
    			$getJobsDetail = $this->getAllJobDetails($user_id);
    			break;
    			/* Designer */
    			case '2':
    			$getJobsDetail = $this->getSpecificJobDetails($user_id, 3);
    			break;
    			/* Measurer */
    			case '3':
    			$getJobsDetail = $this->getSpecificJobDetails($user_id, 2);
    			break;
    			/* Delivery */
    			case '4':
    			$getJobsDetail = $this->getSpecificJobDetails($user_id, 5);
    			break;
    			/* Installer */
    			case '5':
    			$getJobsDetail = $this->getSpecificJobDetails($user_id, 6);
    			break;
    			/* Stone */
    			case '6':
    			$getJobsDetail = $this->getSpecificJobDetails($user_id, 7);
    			break;
    			/* Client */
    			case '9':
    			$getJobsDetail = $this->getAllJobDetails($user_id);
    			break;
    		}
    		return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => 'Get detail successfully', 'response_data' => $getJobsDetail]);

    	} catch (\Exception $e) {}
    }

    /**
     * Get All Job Detail
     *
     * @return Job Detail
     */
    public function getAllJobDetails($user_id)
    {

    	$getDetails = DB::select("SELECT * FROM jobs WHERE company_clients_id LIKE '%{$user_id}%' AND is_deleted = 0 AND is_active = 1 ORDER BY created_at DESC");
    	foreach ($getDetails as $job) {
    		$getStatusName = JobType::selectRaw('job_status_name')->where('job_status_id', $job->job_status_id)->first();
    		$job->job_status_name = $getStatusName->job_status_name;
    	}
    	return $getDetails;
    }

    /**
     * Get get Specific Job Details
     *
     * @return Job Detail
     */
    public function getSpecificJobDetails($user_id, $job_status_id)
    {
    	switch ($job_status_id) {
    		case '5':
    		$orderBy = 'delivery_datetime';
    		break;
    		case '6':
    		$orderBy = 'installation_datetime';
    		break;
    		case '7':
    		$orderBy = 'stone_installation_datetime';
    		break;
    		default:
    		$orderBy = 'created_at';
    		break;
    	}
    	$getDetails = DB::select("SELECT * FROM jobs WHERE company_clients_id LIKE '%{$user_id}%' AND is_deleted = 0 AND is_active = 1 AND job_status_id = '{$job_status_id}' ORDER BY '{$orderBy}' DESC");
    	foreach ($getDetails as $job) {
    		$getStatusName = JobType::selectRaw('job_status_name')->where('job_status_id', $job->job_status_id)->first();
    		$job->job_status_name = $getStatusName->job_status_name;
    	}
    	return $getDetails;
    }

    /*Change job status*/
    public function changeJobStatus(Request $request)
    {
    	try {
    		$validator = Validator::make($request->all(), [
    			'user_id' => 'required',
    			'job_id' => 'required',
    			'user_login_type' => 'required',
    			'user_name' => 'required',
    			'job_status' => 'required',
    		]);
    		if ($validator->fails()) {
    			$messages = $validator->errors()->all();
    			$msg = $messages[0];
    			return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => $msg]);
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
    				/* status */
    				Job::where('job_id', $job_id)->update(['job_status_id' => 3]);
    				/* images */
    				if (sizeof($job_pics) > 0) {
    					$images_data = $this->storeJobImages($job_id, $job_pics);
    					$images_url = implode(',', $images_data[0]);
    					$images_name = implode(',', $images_data[1]);
    					$getExistedImages = Job::selectRaw('job_images_url,job_images_name')->where('job_id', $job_id)->where('is_deleted', 0)->first();

    					if (!empty($getExistedImages)) {
    						if (!empty($getExistedImages->job_images_url)) {
    							$images_url = $getExistedImages->job_images_url . ',' . $images_url;
    						}
    						if (!empty($getExistedImages->job_images_name)) {
    							$images_name = $getExistedImages->job_images_name . ',' . $images_name;
    						}
    					}
    					Job::where('job_id', $job_id)->where('is_deleted', 0)->update(['job_images_url' => $images_url, 'job_images_name' => $images_name]);
    				}
    				/* notes */
    				if (!empty($job_notes)) {
    					$ObjJobNote = new JobNote();
    					$ObjJobNote->job_id = $job_id;
    					$ObjJobNote->name = $user_name;
    					$ObjJobNote->employee_id = $user_id;
    					$ObjJobNote->job_note = $job_notes;
    					$ObjJobNote->login_type_id = $user_login_type;
    					$ObjJobNote->created_at = date('Y-m-d H:i:s');
    					$ObjJobNote->save();
    				}

    				/*send Mail*/
    				$getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
    				$working_employee_ids = explode(',', $getDetail->working_employee_id);
    				$company_client_ids = explode(',', $getDetail->company_clients_id);

    				/*send notification as client */
    				if(sizeof($company_client_ids) > 0)
    				{
    					$title = 'Change Job Status';
    					$badge = '1';
    					$sound = 'default';

    					foreach ($company_client_ids as $client_id) {
    						$device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id);
    						if(!empty($device_detail->device_token)) {
    							$messageBody = $getDetail->job_title .'has been measured and has moved into Design Stage';
    							$deviceid = $device_detail->device_token;
    							$device_type = $device_detail->device_type;
    							$this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
    						}
    					}
    				}
    				/*send mail as measurer*/
    				$this->sendMailDesign($working_employee_ids, $getDetail->job_title);

    				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
    				break;
    				case 2:
    				Job::where('job_id',$job_id)->update(['job_status_id'=> 4]);

    				if(!empty($job_notes)) {
    					$ObjJobNote = new JobNote();
    					$ObjJobNote->job_id = $job_id;
    					$ObjJobNote->name = $user_name;
    					$ObjJobNote->employee_id = $user_id;
    					$ObjJobNote->job_note = $job_notes;
    					$ObjJobNote->login_type_id = $user_login_type;
    					$ObjJobNote->created_at = date('Y-m-d H:i:s');
    					$ObjJobNote->save();
    				}
    				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
    				break;
    				default:
    				return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid job status. Please try again."]);
    				break;
    			}
    			break;
    			case 5:
    			switch ($job_status) {
    				case 1:
    				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
    				break;
    				default:
    				return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid user. Please try again."]);
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
    public function sendMailDesign($working_employee_ids, $job_title)
    {
    	$email_ids = [];
    	foreach ($working_employee_ids as $id) {
    		$email_id = Admin::selectRaw('email')->where('id', $id)->where('login_type_id', 3)->where('is_deleted', 0)->first();
    		if (!empty($email_id)) {
    			$email_ids[] = $email_id->email;
    		}
    	}
    	if (sizeof($email_ids) > 0) {
    		/*send Mail*/
    		Mail::send('emails.AdminPanel_JobDesign', array(
    			'job_title' => $job_title,
    		), function ($message) use ($email_ids, $job_title) {
    			$message->from(env('FromMail', 'askitchen18@gmail.com'), 'A&S KITCHEN');
    			$message->bcc($email_ids)->subject('A&S KITCHEN | ' . $job_title);
    		});
    	}
    	return;
    }

    public function storeJobImages($job_id, $job_pics)
    {
    	/* S3 bucket */
    	if (sizeof($job_pics) > 0) {
    		$imageURL = [];
    		$imageName = [];
    		foreach ($job_pics as $images) {
    			$originalName = $images->getClientOriginalName();
    			$imageFileName = $job_id . '_' . time() . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
    			$s3 = Storage::disk('s3');
    			$filePath = 'jobsite_images/' . $imageFileName;
    			if ($s3->put($filePath, file_get_contents($images), 'public')) {
    				$imageURL[] = $s3->url($filePath);
    				$imageName[] = $imageFileName;
    			}
    		}
    		$result = array($imageURL, $imageName);
    		return $result;
    	}
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
}
