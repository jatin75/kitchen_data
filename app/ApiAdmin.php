<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class ApiAdmin extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'admin_users';
    public $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;
}
