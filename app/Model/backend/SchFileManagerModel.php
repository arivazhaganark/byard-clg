<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class SchFileManagerModel extends Model
{
    public $timestamps = false;
    public  $primaryKey = 'scl_stf_file_id';
    protected $table='at_school_staff_file_manager';
    protected $fillable = ['scl_stf_file_id', 'scl_stf_id', 'scl_stf_sub_id', 'scl_stf_sub_map_id', 'file_name', 'file_type', 'file_permission', 'parent_id', 'academic_year', 'create_time', 'active'];
}
