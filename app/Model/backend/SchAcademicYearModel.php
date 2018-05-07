<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SchAcademicYearModel extends Model
{
    public $timestamps = false;
    protected $table='at_school_academic_year';
    //protected $fillable = ['sch_stu_id', 'sch_cls_id', 'sec_id', 'roll_no', 'academic_year', 'student_name', 'active'];
}
