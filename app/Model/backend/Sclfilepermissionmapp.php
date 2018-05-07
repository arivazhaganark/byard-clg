<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class Sclfilepermissionmapp extends Model
{
    public $timestamps = false;
    protected $table='at_school_file_permission_mapping';
    protected $fillable=['s_map_id', 's_id', 'm_s_id', 'file_add', 'file_edit', 'file_delete', 'active'];
}
