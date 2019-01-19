<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobChecklist extends Model
{
	protected $table = 'jobs_checklists';
	public $primaryKey = 'id';
	public $timestamps = true;
    public $incrementing = true;
}