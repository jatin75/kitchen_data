<?php

namespace App\Http\Controllers\admin;
date_default_timezone_set('UTC');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminHomeController;
use DB;
use URL;
use Hash;
use Session;
use Mail;
use Validator;
use App\Client;
use App\Company;
use App\Admin;

class ClientsController extends Controller
{
	public function index()
	{
		$getClientDetails = DB::select("SELECT cl.client_id,cl.company_id,cl.address_1,cl.city,cl.state,cl.zipcode,cl.contact_preference,cmp.name AS comapny_name,au.email,au.first_name,au.last_name,au.phone_number
			FROM clients AS cl
			JOIN companies AS cmp ON cmp.company_id = cl.company_id
			JOIN admin_users AS au ON au.id = cl.client_id
			WHERE cl.is_deleted = 0 AND au.is_deleted = 0");
		return view('admin.clients')->with('clientDetails',$getClientDetails);
	}

	public function create()
	{
		$companyList = Company::selectRaw('company_id,name')->where('is_deleted',0)->get();
		return view('admin.addclient')->with('companyList',$companyList);
	}

	public function edit($client_id)
	{
		$getClientDetails = DB::select("SELECT cl.client_id,cl.company_id,cl.address_1,cl.address_2,cl.city,cl.state,cl.zipcode,cl.contact_preference,au.email,au.first_name,au.last_name,au.phone_number
			FROM clients AS cl
			JOIN admin_users AS au ON au.id = cl.client_id
			WHERE cl.is_deleted = 0 AND au.is_deleted = 0 AND cl.client_id = '{$client_id}'");
		if(sizeof($getClientDetails) > 0)
		{
			$getClientDetails = $getClientDetails[0];
		}
		$companyList = Company::selectRaw('company_id,name')->where('is_deleted',0)->get();
		return view('admin.addclient')->with('clientDetails',$getClientDetails)->with('companyList',$companyList);
	}

	public function store(Request $request)
	{
		$client_id = $request->get('hidden_client_id');
		$client_email = $request->get('client_email');
		if(!empty($client_id))
		{
			$objClient = Client::where('client_id',$client_id)->first();
			$objAdmin = Admin::where('id',$client_id)->first();
			$checkEmailExist = Admin::selectRaw('email')
			->where('email',$client_email)
			->where('id','<>',$client_id)
			->first();
			if(isset($checkEmailExist->email))
			{
				/*$msg = 'Entered email address already exists.';*/
				$response['key'] = 3;
				echo json_encode($response);
			}
			$objClient->company_id = $request->get('client_company');
			$objClient->address_1 = $request->get('address_1');
			$objClient->address_2 = $request->get('address_2');
			$objClient->city = $request->get('city');
			$objClient->state = $request->get('state');
			$objClient->zipcode = $request->get('zipcode');
			$objClient->contact_preference = $request->get('contact_preference');
			$objClient->save();

			/*Update Session if logged in*/
            $getSessionEmail = Session::get('email');
            if($getSessionEmail == $objAdmin->email)
            {
                Session::pull('name');
                Session::put('name',$request->get('client_first_name')." ".$request->get('client_last_name'));
                $response['name'] = Session::get('name');
            }
			$objAdmin->first_name = $request->get('client_first_name');
			$objAdmin->last_name = $request->get('client_last_name');
			$objAdmin->email = $client_email;
			$objAdmin->phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('client_contactNo'));
			$objAdmin->save();
			$response['key'] = 2;
			echo json_encode($response);
		}
		else
		{

			$checkEmailExist = Admin::selectRaw('email')
			->where('email',$client_email)
			->first();
			if(isset($checkEmailExist->email))
			{
				/*$msg = 'Entered email address already exists.';*/
				$response['key'] = 3;
				echo json_encode($response);
			}
			$new_client_id = (new AdminHomeController)->getuserid();
			$objClient = new Client();
			$objClient->client_id = $new_client_id;
			$objClient->company_id = $request->get('client_company');
			$objClient->address_1 = $request->get('address_1');
			$objClient->address_2 = $request->get('address_2');
			$objClient->city = $request->get('city');
			$objClient->state = $request->get('state');
			$objClient->zipcode = $request->get('zipcode');
			$objClient->contact_preference = $request->get('contact_preference');
			$objClient->is_deleted = 0;
			$objClient->save();

			$objAdmin = new Admin();
			$objAdmin->id = $new_client_id;
			$objAdmin->first_name = $request->get('client_first_name');
			$objAdmin->last_name = $request->get('client_last_name');
			$objAdmin->email = $client_email;
			$objAdmin->password = Hash::make($new_client_id);
			$objAdmin->phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('client_contactNo'));
			$objAdmin->login_type_id = 9;
			$objAdmin->is_deleted = 0;
			$objAdmin->save();

			/*send Mail*/
			/*Mail::send('emails.AdminPanel_EmployeeCreated',array(
				'password' => $new_client_id,
				'email' => $client_email,
			), function($message)use($client_email){
				$message->from(env('FromMail','kitchen@gmail.com'),'KITCHEN');
				$message->to($client_email)->subject('KITCHEN | Client Account Created');
			});*/

			$response['key'] = 1;
			Session::put('successMessage', 'Client detail has been added successfully.');
			echo json_encode($response);
		}
	}

	public function destroy($client_id)
	{
		Client::where('client_id',$client_id)->update(['is_deleted'=>1]);
		Session::flash('successMessage', 'Client has been removed Successfully');
		return redirect()->route('showclients');
	}
}
