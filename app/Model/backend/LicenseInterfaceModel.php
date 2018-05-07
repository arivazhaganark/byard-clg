<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class LicenseInterfaceModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='atnetwork_license_interface';

    protected $fillable=['lin_id', 'interface_name', 'active'];
     
}
