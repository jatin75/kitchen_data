<?php

namespace App\Http\Controllers\admin;

use App\Job;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Exports implements FromCollection, FromQuery, WithHeadings
{
    public function collection()
    {
        $getJobDetails = DB::table('jobs AS jb')
            ->join('job_types AS jbt', 'jbt.job_status_id', '=', 'jb.job_status_id')
            ->selectRaw("jb.job_id,jb.client_id,jb.job_title,jb.address_1,jb.address_2,jb.city,jb.state,jb.zipcode,jb.apartment_number,jb.super_name,jb.super_phone_number,jb.contractor_name,jb.contractor_phone_number,jb.contractor_email,jb.working_employee_id,jb.job_client_id,jb.plumbing_installation_date,jb.delivery_datetime,jb.installation_datetime,jb.installation_employee_id,jb.stone_installation_datetime,jb.stone_installation_employee_id,jb.start_date,jb.end_date,jbt.job_status_name")
            ->where('jb.job_status_id', 1)
            ->get();

        // $getJobDetails = DB::select("SELECT jb.job_id,jb.client_id,jb.job_title,jb.address_1,jb.address_2,jb.city,jb.state,jb.zipcode,jb.apartment_number,jb.super_name,jb.super_phone_number,jb.contractor_name,jb.contractor_phone_number,jb.contractor_email,jb.working_employee_id,jb.job_client_id,jb.plumbing_installation_date,jb.delivery_datetime,jb.installation_datetime,jb.installation_employee_id,jb.stone_installation_datetime,jb.stone_installation_employee_id,jb.start_date,jb.end_date,jbt.job_status_name
        // FROM jobs AS jb
        // JOIN job_types AS jbt ON jbt.job_status_id = jb.job_status_id
        // WHERE jb.job_status_id = 1");
        // foreach ($getJobDetails as $job) {
        //     $excelArray[] = json_decode(json_encode($job), true);
        // }

        return $getJobDetails;
        return Job::where('job_status_id',1)->get();
    }
}
