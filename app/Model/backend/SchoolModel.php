<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SchoolModel extends Model
{
     
     public $timestamps = false;
 
    protected $table='atnetwork_school_master';

    protected $fillable=[ 'scl_id', 'scl_name', 'create_time', 'active'];
     
}
