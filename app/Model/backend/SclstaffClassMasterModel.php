<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SclstaffClassMasterModel extends Model
{
    public $timestamps = false;
    public  $primaryKey = 'sch_cls_stf_id';
    protected $table='at_school_class_staff_master';
    protected $fillable=['sch_cls_stf_id','scl_stf_id','active'];
     
}
