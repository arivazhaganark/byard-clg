<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ClgStaffModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='at_college_staff_master';
    protected $fillable=[ 'cl_stf_id', 'staff_code', 'staff_name', 'active','v_permission','v_s_permission','v_vid_permission','v_vid_s_permission'];
 
}
