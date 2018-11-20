<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
	protected $table = 'check_lists';
	public $primaryKey = 'checklist_id';
	public $timestamps = true;
    public $incrementing = true;
}