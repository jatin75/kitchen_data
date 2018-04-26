<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
	protected $table = 'job_types';
	public $primary_Key = 'id';
	public $timestamps = true;
	public $incrementing = true;
}
