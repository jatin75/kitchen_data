<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	protected $table = 'jobs';
	public $primary_Key = 'job_id';
	public $timestamps = true;
	public $incrementing = false;
}
