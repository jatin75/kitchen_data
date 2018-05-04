<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    public $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
}