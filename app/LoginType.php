<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginType extends Model
{
    protected $table = 'login_types';
    public $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
}
