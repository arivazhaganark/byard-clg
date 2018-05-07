<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ClgFileManagerModel extends Model
{
    public $timestamps = false;
    public  $primaryKey = 'clg_stf_file_id';
    protected $table='at_college_staff_file_manager';
    protected $fillable = ['clg_stf_file_id', 'cl_stf_id', 'clg_stf_sub_id', 'course_id', 'semester_id', 'sub_id', 'file_name', 'file_type', 'file_permission', 'folder_access', 'parent_id', 'academic_year', 'create_time', 'active'];
}
