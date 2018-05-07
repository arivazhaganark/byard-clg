<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ClgDepartModel extends Model
{
     
     public $timestamps = false;
     protected $primaryKey = 'dep_id';
 
    protected $table='at_college_depart_master';
    protected $fillable=[ 'dep_id','gr_id','depart_name','active','division_id'];

    //protected $fillable=[ 'scl_id', 'scl_name', 'create_time', 'active'];
     
}
