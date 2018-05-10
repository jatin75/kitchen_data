<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobNote extends Model
{
    protected $table = 'job_notes';
    public $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
}
