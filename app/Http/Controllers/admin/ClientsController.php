<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
date_default_timezone_set('UTC');
use DB;
use URL;
use Session;
use Mail;
use Validator;
use App\Client;

class ClientsController extends Controller
{
	public function index()
	{
		$getClientDetail = DB::select("SELECT cl.client_id,cl.comapny_id,cl.address_1,cl.city,cl.state,cl.zipcode,cl.contact_preference,cmp.name AS comapny_name,au.email,au.first_name,au.last_name,au.phone_number
			FROM clients AS cl
			JOIN companies AS cmp ON cmp.company_id = cl.comapny_id
			LEFT JOIN admin_users AS au ON au.id = cl.client_id
			WHERE cl.is_deleted = 0 AND au.is_deleted = 0");
		return view('admin.clients')->with('clientDetails',$getClientDetail);
	}

	public function destroy($client_id)
	{
		Client::where('client_id',$client_id)->update(['is_deleted'=>1]);
		Session::flash('successMessage', 'Client has been removed Successfully');
		return redirect()->route('showclients');
	}
}
