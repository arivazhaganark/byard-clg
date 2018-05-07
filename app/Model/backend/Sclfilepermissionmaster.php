<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class Sclfilepermissionmaster extends Model
{
     
    public $timestamps = false;
    public  $primaryKey = 's_id';
    protected $table='at_school_file_permission_master';
    protected $fillable=['s_id','id','active'];

     
     
}
