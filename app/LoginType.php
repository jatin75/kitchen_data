<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginType extends Model
{
    protected $table = 'login_types';
	public $primary_Key = 'id';
	public $timestamps = true;
    public $incrementing = true;
}
