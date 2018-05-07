<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ClgStfSubMapModel extends Model
{
    public $timestamps = false;
    protected $table='at_college_staff_subject_mapping';
    protected $fillable=['clg_stf_sub_map_id','clg_stf_sub_id','course_id','sub_id','create_time','active'];
     
}
