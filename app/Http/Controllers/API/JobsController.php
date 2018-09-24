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
        $employeeList = array();
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
            $employeeList = DB::select("SELECT id,UPPER(CONCAT(first_name,' ',last_name)) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 5");
    		break;
    		/* Installer */
    		case '5':
    		$getJobsDetail = $this->getSpecificJobDetails($user_id, 6);
            $employeeList = DB::select("SELECT id,UPPER(CONCAT(first_name,' ',last_name)) AS employee_name FROM admin_users WHERE is_deleted = 0 AND login_type_id = 6");
    		break;
    		/* Stone */
    		case '6':
    		$getJobsDetail = $this->getSpecificJobDetails($user_id, 7);
    		break;
            /* plumber */
            case '7':
            $getJobsDetail = $this->getAllJobDetails($user_id);
            break;
            /* inspector */
            case '8':
            $getJobsDetail = $this->getAllJobDetails($user_id);
            break;
    		/* Client */
    		case '9':
    		$getJobsDetail = $this->getAllJobDetails($user_id);
    		break;
    	}
    	return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => 'Get detail successfully', 'response_data' => array('job' => $getJobsDetail, 'employee' => $employeeList)]);

    	} catch (\Exception $e) {}
    }

    /**
     * Get All Job Detail
     *
     * @return Job Detail
     */
    public function getAllJobDetails($user_id)
    {
    	$getDetails = DB::select("SELECT * FROM jobs WHERE company_clients_id LIKE '%{$user_id}%' OR working_employee_id LIKE '%{$user_id}%' AND is_deleted = 0 AND is_active = 1 ORDER BY created_at DESC");
    	if(sizeof($getDetails) > 0)
    	{
    		foreach ($getDetails as $job) {
    			$notes = [];
    			$getJobNotes = DB::select("SELECT job_note FROM job_notes WHERE job_id = '{$job->job_id}' AND employee_id = '{$user_id}' AND is_deleted = 0");
    			if(sizeof($getJobNotes) > 0)
    			{
    				foreach($getJobNotes as $note)
    				{
    					$notes[] = $note->job_note;
    				}
    				$job->job_notes = $notes;
    			}
    			else
    			{
    				$job->job_notes = [];
    			}

                $job->delivery_datetime = (!empty($job->delivery_datetime))? date('m/d/Y H:i:s',strtotime($job->delivery_datetime)) : null;
                $job->plumbing_installation_date = (!empty($job->plumbing_installation_date))? date('m/d/Y',strtotime($job->plumbing_installation_date)) : null;
                $job->installation_datetime = (!empty($job->installation_datetime))? date('m/d/Y H:i:s',strtotime($job->installation_datetime)) : null;
                $job->stone_installation_datetime = (!empty($job->stone_installation_datetime))? date('m/d/Y H:i:s',strtotime($job->stone_installation_datetime)) : null;
                $job->start_date = (!empty($job->start_date))? date('m/d/Y',strtotime($job->start_date)) : null;
                $job->end_date = (!empty($job->end_date))? date('m/d/Y',strtotime($job->end_date)) : null;
                $job->created_at = (!empty($job->created_at))? date('m/d/Y H:i:s',strtotime($job->created_at)) : null;
                $job->updated_at = (!empty($job->updated_at))? date('m/d/Y H:i:s',strtotime($job->updated_at)) : null;

    			$getStatusName = JobType::selectRaw('job_status_name')->where('job_status_id', $job->job_status_id)->first();
    			$job->job_status_name = $getStatusName->job_status_name;
    		}
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
        $getDetails = DB::select("SELECT * FROM jobs WHERE working_employee_id LIKE '%{$user_id}%' AND is_deleted = 0 AND is_active = 1 AND job_status_id = '{$job_status_id}' ORDER BY '{$orderBy}' DESC");
        if(sizeof($getDetails) > 0)
    	{
    		foreach ($getDetails as $job) {
    			$notes = [];
    			$getJobNotes = DB::select("SELECT job_note FROM job_notes WHERE job_id = '{$job->job_id}' AND employee_id = '{$user_id}' AND is_deleted = 0");
    			if(sizeof($getJobNotes) > 0)
    			{
    				foreach($getJobNotes as $note)
    				{
    					$notes[] = $note->job_note;
    				}
    				$job->job_notes = $notes;
    			}
    			else
    			{
    				$job->job_notes = [];
    			}

                $job->delivery_datetime = (!empty($job->delivery_datetime))? date('m/d/Y H:i:s',strtotime($job->delivery_datetime)) : null;
                $job->plumbing_installation_date = (!empty($job->plumbing_installation_date))? date('m/d/Y',strtotime($job->plumbing_installation_date)) : null;
                $job->installation_datetime = (!empty($job->installation_datetime))? date('m/d/Y H:i:s',strtotime($job->installation_datetime)) : null;
                $job->stone_installation_datetime = (!empty($job->stone_installation_datetime))? date('m/d/Y H:i:s',strtotime($job->stone_installation_datetime)) : null;
                $job->start_date = (!empty($job->start_date))? date('m/d/Y',strtotime($job->start_date)) : null;
                $job->end_date = (!empty($job->end_date))? date('m/d/Y',strtotime($job->end_date)) : null;
                $job->created_at = (!empty($job->created_at))? date('m/d/Y H:i:s',strtotime($job->created_at)) : null;
                $job->updated_at = (!empty($job->updated_at))? date('m/d/Y H:i:s',strtotime($job->updated_at)) : null;

    			$getStatusName = JobType::selectRaw('job_status_name')->where('job_status_id', $job->job_status_id)->first();
    			$job->job_status_name = $getStatusName->job_status_name;
    		}
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
    		$job_pics_name = $request->get('job_pics_name');
            $job_pics_url = $request->get('job_pics_url');
            $job_notes = $request->get('job_notes');
    		switch ($user_login_type) {
    			/*measurer*/
    			case 3:
    			switch ($job_status) {
    				/*complete*/
    				case 1:
    				/* status */
    				Job::where('job_id', $job_id)->update(['job_status_id' => 3]);

                    /*add notes and images*/
    				$getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
    				if(!empty($job_pics_url)) {
    				    $image_url = explode(',', $job_pics_url);
    				}else{
    					$image_url = array();
    				}
                    /*get job details*/
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
    						$device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
                            if(!empty($device_detail->device_token)) {
                                $messageBody = $getDetail->job_title .' has been measured and has moved into Design Stage';
    							$deviceid = $device_detail->device_token;
    							$device_type = $device_detail->device_type;
                                $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
    						}
    					}
                    }
    				/*send mail as measurer*/
    				$this->sendMailDesign($working_employee_ids, $getDetail->job_title, $job_notes,$image_url);
    				/*send mail as admin*/
    				$adminMailBody = "Job has been measured and is now in Design stage.";
    				$this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

    				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
    				break;

                    /*pending & incomplete*/
    				case 2:
                    /*add notes and images*/
    				$getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);

    				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
    				break;
    				default:
    				return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid job status. Please try again."]);
    				break;
    			}
    			break;
                /*installer*/
    			case 5:
    			switch ($job_status) {
                    /*complete*/
    				case 1:
                    /* status  installationSelect*/
					$is_stone_installation = $request->get('stone_installation_select');

                    if(empty($is_stone_installation)) {
                        return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "The Stone installation field is required."]);
                    }elseif($is_stone_installation == 1 && empty($request->get('stone_installation_date'))) {
                        return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "The Stone installation date field is required."]);
                    }elseif($is_stone_installation == 1 && empty(array_filter($request->get('stoneinstallation_employee')))) {
                        return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "The Stone installation employee field is required."]);
                    }

                    /*add notes and images*/
                    $getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
					if(!empty($job_pics_url)) {
                        $image_url = explode(',', $job_pics_url);
                    }else{
                        $image_url = array();
                    }
                    /*get job details*/
					$getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
					$working_employee_ids = explode(',', $getDetail->working_employee_id);
					$company_client_ids = explode(',', $getDetail->company_clients_id);

                    if($is_stone_installation == 1) {

                        $stoneinstallation_employees = $request->get('stoneinstallation_employee');
                        /*set stone installation datetime*/
                        if(!empty($getDetail->stone_installation_datetime)) {
                            $stoneinstallation_time = date('h:iA', strtotime($getDetail->stone_installation_datetime));
                            $stoneinstallation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' ' . $stoneinstallation_time));
                        }else {
                            $stoneinstallation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' 09:55AM'));
                        }

                        $stone_employee_id = implode(',', $stoneinstallation_employees);
                        $stage = 'Stone installation stage';
                        $job_status = 7;
                        $is_active = 1;

                        $title = 'Change Job Status';
                        $badge = '1';
                        $sound = 'default';

                        /*send notification as stone installer*/
                        foreach ($stoneinstallation_employees as $stoneinstaller_id) {
                            $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$stoneinstaller_id)->first();
                            if(!empty($device_detail->device_token)) {
                                $messageBody = $getDetail->job_title ." has been installed and is awaiting Stone Installation.";
                                $deviceid = $device_detail->device_token;
                                $device_type = $device_detail->device_type;
                                $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                            }
                        }
                    }else {
                        $stone_employee_id = null;
                        $stoneinstallation_datetime = null;
                        $stage = 'Complete';
                        $job_status = 8;
                        $is_active = 0;
                    }

					/*send mail as admin*/
					$adminMailBody = "Job has been Installed and is now in ".$stage.".";
					$this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

					/*send notification as client */
					if(sizeof($company_client_ids) > 0)
					{
						$title = 'Change Job Status';
						$badge = '1';
						$sound = 'default';

						foreach ($company_client_ids as $client_id) {
							$device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
							if(!empty($device_detail->device_token)) {
								$messageBody = $getDetail->job_title ." has been measured and has moved To ".$stage.".";
								$deviceid = $device_detail->device_token;
								$device_type = $device_detail->device_type;
								$this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
							}
						}
					}

                    /*update job*/
					Job::where('job_id', $job_id)->update(['job_status_id' => $job_status,'is_select_stone_installation' => $is_stone_installation,'stone_installation_employee_id' => $stone_employee_id, 'stone_installation_datetime' => $stoneinstallation_datetime, 'is_active' => $is_active]);

    				return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
    				break;
                    /*incomplete*/
    				case 2:

                    /*add notes and images*/
    				$getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
    				if(!empty($job_pics_url)) {
                        $image_url = explode(',', $job_pics_url);
                    }else{
                        $image_url = array();
                    }
                    /*get job details*/
    				$getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
    				$working_employee_ids = explode(',', $getDetail->working_employee_id);
                    $company_client_ids = explode(',', $getDetail->company_clients_id);

                    if(!empty($request->get('installation_date'))) {

                        /*set installation datetime*/
                        if(!empty($getDetail->installation_datetime)) {
                            $installation_time = date('h:iA', strtotime($getDetail->installation_datetime));
                            $installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('installation_date') . ' ' . $installation_time));
                        }else {
                            $installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('installation_date') . ' 09:55AM'));
                        }
                        /*update job*/
                        Job::where('job_id', $job_id)->update(['installation_datetime' => $installation_datetime]);
                        $installation_date = date('m/d/Y', strtotime($request->get('installation_date')));
                        $adminMailBody = "Installation Date has been moved to ". $installation_date.".";

                        /*send notification as client */
                        if(sizeof($company_client_ids) > 0)
                        {
                            $title = 'Change Job Status';
                            $badge = '1';
                            $sound = 'default';

                            foreach ($company_client_ids as $client_id) {
                                $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
                                if(!empty($device_detail->device_token)) {
                                    $messageBody = $getDetail->job_title ." Installation Date is now ". $installation_date;
                                    $deviceid = $device_detail->device_token;
                                    $device_type = $device_detail->device_type;
                                    $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                                }
                            }
                        }
                    }else {
                    	$adminMailBody = "Installation has been changed to INCOMPETE status.";
                    }
                    /*send mail as admin*/
                    $this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
                    break;
                    default:
                    return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid job status. Please try again."]);
                    break;
                }
                break;
                /*Delivery*/
                case 4:
                switch ($job_status) {
                    /*complete*/
                    case 1:
                    /* status  installationSelect*/
                    $is_installation = $request->get('installation_select');

                    if(empty($is_installation)) {
                        return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "The Installation field is required."]);
                    }elseif($is_installation == 1 && empty($request->get('installation_date'))) {
                        return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "The Installation date field is required."]);
                    }elseif($is_installation == 1 && empty(array_filter($request->get('installation_employee')))) {
                        return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "The Installation employee field is required."]);
                    }

                    /*add notes and images*/
                    $getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
                    if(!empty($job_pics_url)) {
                        $image_url = explode(',', $job_pics_url);
                    }else{
                        $image_url = array();
                    }
                    /*get job details*/
                    $getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
                    $working_employee_ids = explode(',', $getDetail->working_employee_id);
                    $company_client_ids = explode(',', $getDetail->company_clients_id);

                    if($is_installation == 1) {

                        $installation_employees = $request->get('installation_employee');
                        $installation_date = $request->get('installation_date');
                        /*set installation datetime*/
                        if(!empty($getDetail->installation_datetime)) {
                            $installation_time = date('h:iA', strtotime($getDetail->installation_datetime));
                            $installation_datetime = date('Y-m-d H:i:s', strtotime($installation_date . ' ' . $installation_time));
                        }else {
                            $installation_datetime = date('Y-m-d H:i:s', strtotime($installation_date . ' 09:55AM'));
                        }
                        $installation_employee_id = implode(',', $installation_employees);
                        $stage = 'Installation stage';
                        $job_status = 6;
                        $is_active = 1;

                        $title = 'Change Job Status';
                        $badge = '1';
                        $sound = 'default';

                        /*send notification as installer*/
                        foreach ($installation_employees as $installer_id) {
                            $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$installer_id)->first();
                            if(!empty($device_detail->device_token)) {
                                $messageBody = $getDetail->job_title ." is ready for Installation";
                                $deviceid = $device_detail->device_token;
                                $device_type = $device_detail->device_type;
                                $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                            }
                        }

                        /*send mail as contractor*/
                        $this->sendMailInstallation($getDetail->job_title,$installation_date,$getDetail->contractor_email);
                    }else {
                        $installation_employee_id = null;
                        $installation_datetime = null;
                        $stage = 'COMPLETE';
                        $job_status = 8;
                        $is_active = 0;
                    }

                    /*send mail as admin*/
                    $adminMailBody = "Job has been delivered and is now In ".$stage.".Please enter INSTALLATION DATE.";
                    $this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

                    /*send notification as client */
                    if(sizeof($company_client_ids) > 0)
                    {
                        $title = 'Change Job Status';
                        $badge = '1';
                        $sound = 'default';

                        foreach ($company_client_ids as $client_id) {
                            $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
                            if(!empty($device_detail->device_token)) {
                                $messageBody = $getDetail->job_title ." has been delivered and Has moved to ".$stage.".";
                                $deviceid = $device_detail->device_token;
                                $device_type = $device_detail->device_type;
                                $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                            }
                        }
                    }

                    /* status */
                    Job::where('job_id', $job_id)->update(['job_status_id' => $job_status,'is_select_installation' => $is_installation, 'installation_employee_id' => $installation_employee_id, 'installation_datetime'=> $installation_datetime,'is_active' => $is_active]);

                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
                    break;
                    /*incomplete*/
                    case 2:

                    /*add notes and images*/
                    $getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
                    if(!empty($job_pics_url)) {
                        $image_url = explode(',', $job_pics_url);
                    }else{
                        $image_url = array();
                    }

                    /*get job details*/
                    $getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
                    $working_employee_ids = explode(',', $getDetail->working_employee_id);
                    $company_client_ids = explode(',', $getDetail->company_clients_id);
                    $delivery_time = date('h:iA', strtotime($getDetail->delivery_datetime));

                    if(!empty($request->get('delivery_date'))) {

                        /*update delivery datetime*/
                        $delivery_datetime = date('Y-m-d H:i:s', strtotime($request->get('delivery_date') . ' ' . $delivery_time));
                        Job::where('job_id', $job_id)->update(['delivery_datetime' => $delivery_datetime]);
                        $delivery_date = date('m/d/Y', strtotime($request->get('delivery_date')));
                        $adminMailBody = "Delivery Date has been moved to ". $delivery_date.".";

                        /*send notification as client */
                        if(sizeof($company_client_ids) > 0)
                        {
                            $title = 'Change Job Status';
                            $badge = '1';
                            $sound = 'default';

                            foreach ($company_client_ids as $client_id) {
                                $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
                                if(!empty($device_detail->device_token)) {
                                    $messageBody = $getDetail->job_title ." Delivery Date is now ". $delivery_date;
                                    $deviceid = $device_detail->device_token;
                                    $device_type = $device_detail->device_type;
                                    $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                                }
                            }
                        }
                    }else {
                        $adminMailBody = "Delivery has been changed to INCOMPETE status.";
                    }
                    /*send mail as admin*/
                    $this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
                    break;
                    default:
                    return response()->json(['success_code' => 200, 'response_code' => 1, 'response_message' => "Invalid job status. Please try again."]);
                    break;
                }
                break;
                /*stone installer*/
                case 6:
                switch ($job_status) {
                    /*complete*/
                    case 1:
                    /*add notes and images*/
                    $getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
                    if(!empty($job_pics_url)) {
                        $image_url = explode(',', $job_pics_url);
                    }else{
                        $image_url = array();
                    }
                    /*get job details*/
                    $getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
                    $working_employee_ids = explode(',', $getDetail->working_employee_id);
                    $company_client_ids = explode(',', $getDetail->company_clients_id);

                    /*send mail as admin*/
                    $adminMailBody = "Job STONE has been installed and is now COMPLETE.";
                    $this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

                    /*send notification as client */
                    if(sizeof($company_client_ids) > 0)
                    {
                        $title = 'Change Job Status';
                        $badge = '1';
                        $sound = 'default';

                        foreach ($company_client_ids as $client_id) {
                            $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
                            if(!empty($device_detail->device_token)) {
                                $messageBody = $getDetail->job_title ." is COMPLETE.";
                                $deviceid = $device_detail->device_token;
                                $device_type = $device_detail->device_type;
                                $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                            }
                        }
                    }
                    /*update job*/
                    Job::where('job_id', $job_id)->update(['job_status_id' => 8, 'is_active' => 0]);

                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
                    break;
                    /*incomplete*/
                    case 2:
                    /*add notes and images*/
                    $getImageNote = $this->storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url);
                    if(!empty($job_pics_url)) {
                        $image_url = explode(',', $job_pics_url);
                    }else{
                        $image_url = array();
                    }
                    /*get job details*/
                    $getDetail = Job::where('job_id', $job_id)->where('is_deleted', 0)->first();
                    $working_employee_ids = explode(',', $getDetail->working_employee_id);
                    $company_client_ids = explode(',', $getDetail->company_clients_id);

                    if(!empty($request->get('stone_installation_date'))) {

                        /*set stone installation datetime*/
                        if(!empty($getDetail->stone_installation_datetime)) {
                            $stone_installation_time = date('h:iA', strtotime($getDetail->stone_installation_datetime));
                            $stone_installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' ' . $stone_installation_time));
                        }else {
                            $stone_installation_datetime = date('Y-m-d H:i:s', strtotime($request->get('stone_installation_date') . ' 09:55AM'));
                        }

                        /*update job*/
                        Job::where('job_id', $job_id)->update(['stone_installation_datetime' => $stone_installation_datetime]);
                        $stone_installation_date = date('m/d/Y', strtotime($request->get('stone_installation_date')));
                        $adminMailBody = " Stone Installation Date has been moved to ". $stone_installation_date.".";

                        /*send notification as client */
                        if(sizeof($company_client_ids) > 0)
                        {
                            $title = 'Change Job Status';
                            $badge = '1';
                            $sound = 'default';

                            foreach ($company_client_ids as $client_id) {
                                $device_detail = Admin::selectRaw('device_token,device_type')->where('id',$client_id)->first();
                                if(!empty($device_detail->device_token)) {
                                    $messageBody = $getDetail->job_title ." Stone Installation Date is now ". $stone_installation_date;
                                    $deviceid = $device_detail->device_token;
                                    $device_type = $device_detail->device_type;
                                    $this->pushNotification($deviceid,$device_type,$messageBody,$title,$badge,$sound);
                                }
                            }
                        }
                    }else {
                        $adminMailBody = " Stone Installation has been changed to INCOMPETE status.";
                    }
                    /*send mail as admin*/
                    $this->sendMailAdmin($working_employee_ids, $getDetail->job_title, $job_notes,$adminMailBody,$image_url);

                    return response()->json(['success_code' => 200, 'response_code' => 0, 'response_message' => "Job status changed successfully"]);
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
    public function sendMailDesign($working_employee_ids, $job_title, $job_notes,$image_url="")
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
                'job_note' => $job_notes,
    		), function ($message) use ($email_ids, $job_title,$image_url) {
    			$message->from(env('FromMail', 'askitchen18@gmail.com'), 'A&S KITCHEN');
    			$message->bcc($email_ids)->subject('A&S KITCHEN | ' . $job_title);
                if(count($image_url) > 0) {
                    for($i=0; $i<count($image_url); $i++) {
                        $message->attach($image_url[$i]);
                    }
                }
    		});
    	}
    	return;
    }

    /* storeJobNotesAndImage */
    public function storeJobNotesAndImage($user_id,$user_name,$job_id,$user_login_type,$job_notes,$job_pics_name,$job_pics_url) {
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
    	/* images */
    	if (!empty($job_pics_name)  && !empty($job_pics_url)) {
    		//$images_data = $this->storeJobImages($job_id, $job_pics);
    		$images_url = $job_pics_url;
            $images_name = $job_pics_name;
    		$getExistedImages = Job::selectRaw('job_images_url,job_images_name')->where('job_id', $job_id)->where('is_deleted', 0)->first();

    		if (!empty($getExistedImages)) {
    			if (!empty($getExistedImages->job_images_url)) {
    				$images_url = $getExistedImages->job_images_url . ',' . $job_pics_url;
    			}
    			if (!empty($getExistedImages->job_images_name)) {
    				$images_name = $getExistedImages->job_images_name . ',' . $job_pics_name;
    			}
    		}
    		Job::where('job_id', $job_id)->where('is_deleted', 0)->update(['job_images_url' => $images_url, 'job_images_name' => $images_name]);

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
    			//'badge' => $badge,
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

    /* Send mail admin */
    public function sendMailAdmin($working_employee_ids, $job_title, $job_notes, $adminMailBody,$image_url="")
    {
        $email_ids = [];
    	foreach ($working_employee_ids as $id) {
    		$email_id = Admin::selectRaw('email')->where('id', $id)->where('login_type_id', 1)->where('is_deleted', 0)->first();
    		if (!empty($email_id)) {
    			$email_ids[] = $email_id->email;
    		}
    	}
    	if (sizeof($email_ids) > 0) {
    		/* send Mail*/
    		Mail::send('emails.KitchenApp_AdminEmail', array(
    			'job_title' => $job_title,
    			'job_note' => $job_notes,
    			'mail_body' => $adminMailBody,
    		), function ($message) use ($email_ids, $job_title,$image_url) {
    			$message->from(env('FromMail', 'askitchen18@gmail.com'), 'A&S KITCHEN');
    			$message->bcc($email_ids)->subject('A&S KITCHEN | ' . $job_title);
    			if(count($image_url) > 0) {
    				for($i=0; $i<count($image_url); $i++) {
    					$message->attach($image_url[$i]);
    				}
    			}
    		});
    	}
    	return;
    }

    /* send mail as installation contractor */
    function sendMailInstallation($job_title,$installation_datetime,$contractor_email)
    {
        if(!empty($installation_datetime)) {
            $installation_date = date('m/d/Y', strtotime($installation_datetime));
        } else {
            $installation_date = '';
        }

        /*Contractor*/
        if(!empty($contractor_email))
        {
            /*send Mail*/
            Mail::send('emails.KitchenApp_JobInstalling',array(
                'job_title' =>  $job_title,
                'installation_date' =>  $installation_date,
                ), function($message)use($contractor_email, $job_title){
                $message->from(env('FromMail','askitchen18@gmail.com'),'A&S KITCHEN');
                $message->bcc($contractor_email)->subject('A&S KITCHEN | '.$job_title);
            });
        }
        return;
    }
}
