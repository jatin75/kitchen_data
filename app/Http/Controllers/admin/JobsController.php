<?php

namespace App\Http\Controllers\admin;

date_default_timezone_set('UTC');
use App\Admin;
use App\Http\Controllers\Controller;
use App\Job;
use Session;

class JobsController extends Controller
{
    public function index()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,client_id,job_status_id,start_date,end_date')->where('is_active', 1)->where('is_deleted', 0)->get();
        foreach ($getJobDetails as $jobs) {
            $getClientName = Admin::selectRaw('first_name,last_name')->where('id', $jobs->client_id)->first();
            $jobs->client_name = $getClientName->first_name . ' ' . $getClientName->last_name;
        }
        return view('admin.jobs')->with('jobDetails', $getJobDetails);
    }

    public function showDeactivated()
    {
        $getJobDetails = Job::selectRaw('job_id,job_title,client_id,job_status_id,start_date,end_date')->where('is_active', 0)->where('is_deleted', 0)->get();
        foreach ($getJobDetails as $jobs) {
            $getClientName = Admin::selectRaw('first_name,last_name')->where('id', $jobs->client_id)->first();
            $jobs->client_name = $getClientName->first_name . ' ' . $getClientName->last_name;
        }
        return view('admin.deactivatedjobs')->with('jobDetails', $getJobDetails);
    }

    public function edit($job_id)
    {
        return view('admin.deactivatedjobs');
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
            $this->getTeamId();
        }
    }
}
