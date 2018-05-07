<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SchSectionMappingModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='at_school_section_mapping_master';

    //protected $fillable=['sec_id','sch_cls_id','section_name','active'];
     
}
