<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SclStfSubMasterModel extends Model
{
    public $timestamps = false;
    public  $primaryKey = 'scl_stf_sub_id';
    protected $table='at_school_staff_subject_master';
    protected $fillable = ['scl_stf_sub_id','scl_stf_id','active'];
}
