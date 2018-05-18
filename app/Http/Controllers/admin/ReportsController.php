<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminHomeController;
use App\JobType;
date_default_timezone_set('UTC');
use DB;
use Illuminate\Http\Request;
//use Maatwebsite\Excel\Facades\Excel;
//use Maatwebsite\Excel\Concerns\FromCollection;
//require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use File;

class ReportsController extends Controller
{
  public function index()
  {
    return view('admin.reports')->with('jobStatusList', JobType::all());
  }

  public function downloadJobExcel(Request $request)
  {
    //$status_id = $request->get('status_id');
    $status_id = $request->get('jobStatus');
    if($status_id == 0) {
      $jobStatusCond = "";
    }else {
      $jobStatusCond = "WHERE jb.job_status_id = {$status_id}";
    }
    $getJobDetails = DB::select("SELECT jb.job_id,jb.company_id,jb.job_title,jb.address_1,jb.address_2,jb.city,jb.state,jb.zipcode,jb.apartment_number,jb.super_name,jb.super_phone_number,jb.contractor_name,jb.contractor_phone_number,jb.contractor_email,jb.working_employee_id,jb.company_clients_id,DATE_FORMAT(jb.plumbing_installation_date, '%m/%d/%Y') as plumbing_installation_date,DATE_FORMAT(jb.delivery_datetime, '%m/%d/%Y %h:%i%p') as delivery_datetime,DATE_FORMAT(jb.installation_datetime, '%m/%d/%Y %h:%i%p') as installation_datetime,jb.installation_employee_id,DATE_FORMAT(jb.stone_installation_datetime, '%m/%d/%Y %h:%i%p') as stone_installation_datetime,jb.stone_installation_employee_id,DATE_FORMAT(jb.start_date, '%m/%d/%Y') as start_date,DATE_FORMAT(jb.end_date, '%m/%d/%Y') as end_date,jbt.job_status_name
      FROM jobs AS jb
      JOIN job_types AS jbt ON jbt.job_status_id = jb.job_status_id
      {$jobStatusCond}");

      foreach($getJobDetails as $data)
      {
        $data->super_phone_number = (!empty($data->super_phone_number)) ? (new AdminHomeController)->formatPhoneNumber($data->super_phone_number) : '';
        $data->contractor_phone_number = (!empty($data->contractor_phone_number)) ? (new AdminHomeController)->formatPhoneNumber($data->contractor_phone_number) : '';

      }

    $getJobDetails = json_decode(json_encode($getJobDetails), true);
    array_unshift($getJobDetails,array('job_id'=>'Job id','company_id'=>'Company id','job_title'=>'Job title','address_1'=>'Address1','address_2'=>'Address2','city'=>'City','state'=>'State','zipcode'=>'Zipcode','apartment_number'=>'Apartment number','super_name'=>'Super name','super_phone_number'=>'Super phone number','contractor_name'=>'Contractor name','contractor_phone_number'=>'Contractor phone number','contractor_email'=>'Contractor email','working_employee_id'=>'Working employee id','company_clients_id'=>'Company client id','plumbing_installation_date'=>'Plumbing installation date','delivery_datetime'=>'Delivery datetime','installation_datetime'=>'Installation datetime','installation_employee_id'=>'Installation employee id','stone_installation_datetime'=>'Stone installation datetime','Stone_installation_employee_id'=>'Stone installation employee id','start_date'=>'Start date','end_date'=>'End date','job_status_name'=>'Job status name'));

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $styleArray = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      ],
    ];

    $sheet->getStyle('A1:Y1')->applyFromArray($styleArray);
    $sheet->fromArray($getJobDetails,NULL);
    $writer = new Xlsx($spreadsheet);

    /*header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="kitchen_Jobs.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');*/
    File::cleanDirectory('assets/excel_files/');
    $writer->save('assets/excel_files/kitchen_Jobs_'.time().'.xlsx');

    $path = public_path('assets/excel_files/kitchen_Jobs_'.time().'.xlsx');
    $headers = array('Content-Type' => 'application/octet-stream');
    if(File::exists($path)){
      return response()->download($path,'kitchen_Jobs_'.date('Y_m_d').'.xlsx',$headers);
    }else{
      return back();
    }
    //File::delete($path);

    /*$response['key'] = 1;
    echo json_encode($response);*/
  }
}