<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SchClassModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='at_school_class_master';

    protected $fillable=[  'sch_cls_id', 'sch_class', 'active'];
     
}
