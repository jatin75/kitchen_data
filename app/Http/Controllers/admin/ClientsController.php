<?php
namespace App\Http\Controllers\admin;

date_default_timezone_set('UTC');
use App\Admin;
use App\Client;
use App\Company;
use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Session;

class ClientsController extends Controller
{
    public function index()
    {
        $getClientDetails = DB::select("SELECT cl.client_id,cl.company_id,cl.address_1,cl.city,cl.state,cl.zipcode,cl.contact_preference,cmp.name AS comapny_name,au.email,au.first_name,au.last_name,au.phone_number,au.secondary_email,au.secondary_phone_number, cl.note_status
			FROM clients AS cl
			JOIN companies AS cmp ON cmp.company_id = cl.company_id
			JOIN admin_users AS au ON au.id = cl.client_id
            WHERE cl.is_deleted = 0 AND au.is_deleted = 0 AND au.id <> 'UYJ13459' ORDER BY au.created_at DESC");
        if(Session::get('login_type_id') == 1  || Session::get('login_type_id') == 2 ) {
            return view('admin.clients')->with('clientDetails', $getClientDetails);
        }else {
            return redirect(route('dashboard'));
        }
    }

    public function create()
    {
        $companyList = Company::selectRaw('company_id,name')->where('is_deleted', 0)->get();
        return view('admin.addclient')->with('companyList', $companyList);
    }

    public function edit($client_id)
    {
        $getClientDetails = DB::select("SELECT cl.client_id,cl.company_id,cl.address_1,cl.address_2,cl.city,cl.state,cl.zipcode,cl.contact_preference,au.email,au.first_name,au.last_name,au.phone_number,au.secondary_phone_number, au.secondary_email
			FROM clients AS cl
			JOIN admin_users AS au ON au.id = cl.client_id
			WHERE cl.is_deleted = 0 AND au.is_deleted = 0 AND cl.client_id = '{$client_id}'");
        if (sizeof($getClientDetails) > 0) {
            $getClientDetails = $getClientDetails[0];
        }
        $companyList = Company::selectRaw('company_id,name')->where('is_deleted', 0)->get();
        return view('admin.addclient')->with('clientDetails', $getClientDetails)->with('companyList', $companyList);
    }

    public function store(Request $request)
    {
        $client_id = $request->get('hidden_client_id');
        $client_email = $request->get('client_email');
        if (!empty($client_id)) {
            $objClient = Client::where('client_id', $client_id)->first();
            $objAdmin = Admin::where('id', $client_id)->first();
            $checkEmailExist = Admin::selectRaw('email')
                ->where('email', $client_email)
                ->where('id', '<>', $client_id)
                ->first();
            if (isset($checkEmailExist->email)) {
                /*$msg = 'Entered email address already exists.';*/
                $response['key'] = 3;
                return json_encode($response);
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
            if ($getSessionEmail == $objAdmin->email) {
                Session::pull('name');
                Session::put('name', $request->get('client_first_name') . " " . $request->get('client_last_name'));
                Session::pull('email');
                Session::put('email', $client_email);
                $response['name'] = Session::get('name');
                $response['email'] = $client_email;
            }
            $objAdmin->first_name = $request->get('client_first_name');
            $objAdmin->last_name = $request->get('client_last_name');
            $objAdmin->email = $client_email;
            $objAdmin->phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('client_contactNo'));
            if(empty($request->get('client_secondContact')) || $request->get('client_secondContact') == ''){
                $objAdmin->secondary_phone_number = '';
            }else {
                $objAdmin->secondary_phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('client_secondContact'));
            }
            $objAdmin->secondary_email = $request->get('client_secondEmail');
            $objAdmin->save();
            $response['key'] = 2;
            return json_encode($response);
        } else {

            $checkEmailExist = Admin::selectRaw('email')
                ->where('email', $client_email)
                ->first();
            if (isset($checkEmailExist->email)) {
                /*$msg = 'Entered email address already exists.';*/
                $response['key'] = 3;
                return json_encode($response);
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
            $objClient->created_at = date('Y-m-d H:i:s');
            $objClient->save();

            $objAdmin = new Admin();
            $objAdmin->id = $new_client_id;
            $objAdmin->first_name = $request->get('client_first_name');
            $objAdmin->last_name = $request->get('client_last_name');
            $objAdmin->email = $client_email;
            $objAdmin->password = Hash::make($new_client_id);
            $objAdmin->phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('client_contactNo'));
            if(empty($request->get('client_secondContact')) || $request->get('client_secondContact') == ''){
                $objAdmin->secondary_phone_number = '';
            }else {
                $objAdmin->secondary_phone_number = (new AdminHomeController)->replacePhoneNumber($request->get('client_secondContact'));
            }
            $objAdmin->secondary_email = $request->get('client_secondEmail');
            $objAdmin->login_type_id = 9;
            $objAdmin->is_deleted = 0;
            $objAdmin->created_at = date('Y-m-d H:i:s');
            $objAdmin->save();

            /*send Mail*/
            Mail::send('emails.AdminPanel_ClientCreated', array(
                'password' => $new_client_id,
                'email' => $client_email,
            ), function ($message) use ($client_email) {
                $message->from(env('FromMail', 'askitchen18@gmail.com'), 'A&S KITCHEN');
                $message->to($client_email)->subject('A&S KITCHEN | Client Account Created');
            });

            $response['key'] = 1;
            Session::put('successMessage', 'Client detail has been added successfully.');
            return json_encode($response);
        }
    }

    public function destroy($client_id)
    {
        Client::where('client_id', $client_id)->update(['is_deleted' => 1]);
        Admin::where('id', $client_id)->update(['is_deleted' => 1]);
        Session::flash('successMessage', 'Client has been removed Successfully');
        return redirect()->route('showclients');
    }

    public function getCompanyClients(Request $request)
    {
        $company_id = $request->get('company_id');
        $getClients = DB::select("SELECT UPPER(CONCAT(au.first_name,' ',au.last_name)) AS client_name,au.id FROM clients AS cl JOIN admin_users AS au ON au.id = cl.client_id  And au.is_deleted = 0 And au.id <> 'UYJ13459' WHERE cl.company_id = '{$company_id}'");
        if (sizeof($getClients) > 0) {
            $response['clients_data'] = $getClients;
            $response['key'] = 1;
        } else {
            $response['key'] = 2;
        }
        return json_encode($response);
    }

    public function editMyProfile($id)
    {
        $getClientDetails = DB::select("SELECT cl.client_id,cl.company_id,cl.address_1,cl.address_2,cl.city,cl.state,cl.zipcode,cl.contact_preference,au.email,au.first_name,au.last_name,au.phone_number
			FROM clients AS cl
			JOIN admin_users AS au ON au.id = cl.client_id
			WHERE cl.is_deleted = 0 AND au.is_deleted = 0 AND au.id = '{$id}'");
        if (sizeof($getClientDetails) > 0) {
            $getClientDetails = $getClientDetails[0];
        }
        $companyList = Company::selectRaw('company_id,name')->where('is_deleted', 0)->get();
        return view('admin.addclient')->with('clientDetails', $getClientDetails)->with('companyList', $companyList)->with('accountSetting', 1);
    }

    public function change_client_noteStatus(Request $request) {
        $client_id = $request->get('client_id');
        $note_status = $request->get('note_status');
        Client::where('client_id', $client_id)->update(['note_status' => $note_status]);
        $response['key'] = 1;
        return json_encode($response);
    }
}
