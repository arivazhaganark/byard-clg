<?php

namespace App\Model\frontend;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{	
	protected $primaryKey = 'f_id';
	//public $timestamps = false;
	protected $table = 'feedback';
	protected $fillable = [
        'subject', 'message', 'user_id', 'status'
    ];
}
