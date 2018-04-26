<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
	public $primary_Key = 'id';
	public $timestamps = true;
    public $incrementing = true;
}
