<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminHomeController;
date_default_timezone_set('UTC');
use DB;
use URL;
use Session;
use Mail;
use Validator;
use App\Company;
use App\Job;
use App\Admin;
use App\Client;

class AdministrationController extends Controller
{
	public function index() {
		$client_company = Company::selectRaw('name,phone_number,address_1,email,created_at,id,company_id')->where('is_deleted',0)->orderBy('created_at', 'DESC')->get();
		return view('admin.clientcompany')->with('clientCompanyList',$client_company);
	}

	public function create() {
		return view('admin.addclientcompany');
	}

	public function store(Request $request) {
		$hidden_companyId = $request->get('hidden_companyId');
		$company_name = $request->get('company_name');
		$company_contactNo = $request->get('company_contactNo');
		$company_email = $request->get('company_email');
		$company_address_1 = $request->get('company_address_1');
		$company_address_2 = $request->get('company_address_2');
		$city = $request->get('city');
		$state = $request->get('state');
		$zipcode = $request->get('zipcode');

		if(!empty($hidden_companyId))
		{
			$getDetail = Company::where('company_id',$hidden_companyId)->first();
			$getDetail->name = $company_name;
			$getDetail->phone_number = (new AdminHomeController)->replacePhoneNumber($company_contactNo);
			$getDetail->email = $company_email;
			$getDetail->address_1 = $company_address_1;
			$getDetail->address_2 = $company_address_2;
			$getDetail->city = $city;
			$getDetail->state = $state;
			$getDetail->zipcode = $zipcode;
			$getDetail->save();
			$response['key'] = 2;
			//Session::put('successMessage', 'Company detail has been updated successfully.');
			echo json_encode($response);
		}
		else
		{
			$companyId = $this->getCompanyId();
			$objCompany = new Company();
			$objCompany->company_id = $companyId;
			$objCompany->name = $company_name;
			$objCompany->phone_number = (new AdminHomeController)->replacePhoneNumber($company_contactNo);
			$objCompany->email = $company_email;
			$objCompany->address_1 = $company_address_1;
			$objCompany->address_2 = $company_address_2;
			$objCompany->city = $city;
			$objCompany->state = $state;
			$objCompany->zipcode = $zipcode;
			$objCompany->is_deleted = 0;
			$objCompany->created_at = date('Y-m-d H:i:s');
			$objCompany->save();
			$response['key'] = 1;
			Session::put('successMessage', 'Company detail has been added successfully.');
			echo json_encode($response);
		}
	}

	public function destroy($company_id)
	{
		Company::where('company_id',$company_id)->update(['is_deleted' => 1]);
		Job::where('company_id',$company_id)->update(['is_deleted' => 1]);
		/*Client::where('company_id',$company_id)->update(['is_deleted' => 1]);
		$getClient = Client::selectRaw('client_id')->where('company_id',$company_id)->get();
		if(sizeof($getClient) > 0) {
			for($i=0; $i<sizeof($getClient); $i++) {
				$client_id = $getClient[$i]->client_id;
				Admin::where('id',$client_id)->update(['is_deleted' => 1]);
			}
		}*/
				
		$msg = 'Company deleted successfully.';
		Session::flash('successMessage',$msg);
		return back();
	}

	public function edit($company_id) {
		$getCompanyDetail = Company::selectRaw('name,phone_number,address_1,address_2,city,state,zipcode,email,created_at,company_id,id')->where('company_id',$company_id)->get();
		if(sizeof($getCompanyDetail) > 0)
		{
			$getCompanyDetail = $getCompanyDetail[0];
		}
		return view('admin.addclientcompany')->with('companyDetail',$getCompanyDetail);
	}

	function getCompanyId()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 1; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $company_id = $randomString.mt_rand(1000000,9999999);
        $check = Company::where('company_id',$company_id)->first();
        if (empty($check)){
            return $company_id;
        } else {
            $this->getLeagueId();
        }
    }
}