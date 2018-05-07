<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ClgStfSubMasterModel extends Model
{
    public $timestamps = false;
    public  $primaryKey = 'clg_stf_sub_id';
    protected $table='at_college_staff_subject_master';
    protected $fillable = ['clg_stf_sub_id','cl_stf_id','active'];
}
