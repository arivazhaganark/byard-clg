<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class Clgfilepermissionmaster extends Model
{
     
    public $timestamps = false;
    public  $primaryKey = 'p_id';
    protected $table='at_college_file_permission_master';
    protected $fillable=['p_id','id','active'];

     
     
}
