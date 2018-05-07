<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class Clgfilepermissionmapp extends Model
{
    public $timestamps = false;
    protected $table='at_college_file_permission_mapping';
    protected $fillable=['f_map_id', 'p_id', 'm_c_id', 'file_add', 'file_edit', 'file_delete', 'active'];
}
