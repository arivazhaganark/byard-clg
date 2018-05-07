<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class EducationTypeModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='at_education_type_master';

    protected $fillable=['ed_id', 'ed_type', 'active'];
     
}
