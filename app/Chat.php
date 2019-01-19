<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat_history';
	public $primaryKey = 'id';
	public $timestamps = true;
    public $incrementing = true;
}
