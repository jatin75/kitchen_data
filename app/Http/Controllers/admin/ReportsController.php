<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\JobType;
date_default_timezone_set('UTC');
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
//require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportsController extends Controller
{
    public function index()
    {

      /*$getJobDetails = DB::select("SELECT jb.job_id,jb.client_id,jb.job_title,jb.address_1,jb.address_2,jb.city,jb.state,jb.zipcode,jb.apartment_number,jb.super_name,jb.super_phone_number,jb.contractor_name,jb.contractor_phone_number,jb.contractor_email,jb.working_employee_id,jb.job_client_id,jb.plumbing_installation_date,jb.delivery_datetime,jb.installation_datetime,jb.installation_employee_id,jb.stone_installation_datetime,jb.stone_installation_employee_id,jb.start_date,jb.end_date,jbt.job_status_name
          FROM jobs AS jb
          JOIN job_types AS jbt ON jbt.job_status_id = jb.job_status_id
          WHERE jb.job_status_id = 1");

      $getJobDetails = json_decode(json_encode($getJobDetails), true);
      array_unshift($getJobDetails,array('job_id'=>'job_id','client_id'=>'client_id','job_title'=>'job_title','address_1'=>'address_1','address_2'=>'address_2','city'=>'city','state'=>'state','zipcode'=>'zipcode','apartment_number'=>'apartment_number','super_name'=>'super_name','super_phone_number'=>'super_phone_number','contractor_name'=>'contractor_name','contractor_phone_number'=>'contractor_phone_number','contractor_email'=>'contractor_email','working_employee_id'=>'working_employee_id','job_client_id'=>'job_client_id','plumbing_installation_date'=>'plumbing_installation_date','delivery_datetime'=>'delivery_datetime','installation_datetime'=>'installation_datetime','installation_employee_id'=>'installation_employee_id','stone_installation_datetime'=>'stone_installation_datetime','stone_installation_employee_id'=>'stone_installation_employee_id','start_date'=>'start_date','end_date'=>'end_date','job_status_name'=>'job_status_name'));
      
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
      $sheet->fromArray($getJobDetails);
      $writer = new Xlsx($spreadsheet);
      $writer->save('kitchen_Jobs_'.time().'.xlsx');*/

        // $excelArray = [];
        // $excelArray[] = ['job_id', 'client_id', 'job_title', 'address_1', 'address_2', 'city', 'state', 'zipcode', 'apartment_number', 'super_name', 'super_phone_number', 'contractor_name', 'contractor_phone_number', 'contractor_email', 'working_employee_id', 'job_client_id', 'plumbing_installation_date', 'delivery_datetime', 'installation_datetime', 'installation_employee_id', 'stone_installation_datetime', 'stone_installation_employee_id', 'start_date', 'end_date', 'job_status_name'];

        /*$excelDetails = new Exports();
        $getDetail = $excelDetails->collection();
        echo '<pre>';
        $getDetail = json_decode(json_encode($getDetail), true);
        array_unshift($getDetail,array('job_id'=>'job_id','client_id'=>'client_id'));
        print_r($getDetail);
        die;*/
        //return Excel::download(new Exports, 'joblist.xlsx');

        // foreach ($getJobDetails as $job) {
        //     $excelArray[] = json_decode(json_encode($job), true);
        // }
        // return Excel::download(new InvoicesExport, 'invoices.xlsx');
        // return response()->download($getJobDetails, 'Kitchen_Jobs_' . date('Y_m_d') . '.xlsx');

        return view('admin.reports')->with('jobStatusList', JobType::all());
    }

    public function downloadJobExcel(Request $request)
    {
        $status_id = $requets->get('status_id');

        $getJobDetails = DB::select("SELECT jb.job_id,jb.client_id,jb.job_title,jb.address_1,jb.address_2,jb.city,jb.state,jb.zipcode,jb.apartment_number,jb.super_name,jb.super_phone_number,jb.contractor_name,jb.contractor_phone_number,jb.contractor_email,jb.working_employee_id,jb.job_client_id,jb.plumbing_installation_date,jb.delivery_datetime,jb.installation_datetime,jb.installation_employee_id,jb.stone_installation_datetime,jb.stone_installation_employee_id,jb.start_date,jb.end_date,jbt.job_status_name
            FROM jobs AS jb
            JOIN job_types AS jbt ON jbt.job_status_id = jb.job_status_id
            WHERE jb.job_status_id = '{$status_id}'");

        $path = public_path('/admin/csv/prospect.csv');
        return response()->download('Kitchen_Jobss_' . date('Y_m_d') . '.xlsx');
    }
}
