<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SchSectionModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='at_school_section_master';

    protected $fillable=['sec_id','section_name','active'];
     
}
