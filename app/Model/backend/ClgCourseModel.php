<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ClgCourseModel extends Model
{
     
    public $timestamps = false;
    protected $primaryKey = 'course_id';
 
    protected $table='at_college_course_master';
    protected $fillable=[ 'course_id', 'gr_id','year_id', 'dep_id', 'course_name', 'active'];
     
     
     
}
