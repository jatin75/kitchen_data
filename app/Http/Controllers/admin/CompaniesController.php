<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;

class CompaniesController extends Controller
{
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
