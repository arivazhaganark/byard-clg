<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SclStfSubMapModel extends Model
{
    public $timestamps = false;
    protected $table='at_school_staff_subject_mapping';
    protected $fillable=['scl_stf_sub_map_id','scl_stf_sub_id','sch_cls_id','sec_id','sub_id','active'];
     
}
