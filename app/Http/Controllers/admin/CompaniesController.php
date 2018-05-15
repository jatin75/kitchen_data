<?php

namespace App\Http\Controllers\admin;

use App\Company;
use App\Http\Controllers\Controller;
use Mail;

class CompaniesController extends Controller
{
    public function getCompanyId()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 1; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $company_id = $randomString . mt_rand(1000000, 9999999);
        $check = Company::where('company_id', $company_id)->first();
        if (empty($check)) {
            return $company_id;
        } else {
            $this->getLeagueId();
        }
    }
}