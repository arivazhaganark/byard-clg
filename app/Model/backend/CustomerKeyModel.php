<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class CustomerKeyModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='atnetwork_customer_key_master';

    protected $fillable=[ 'cus_key_id', 'cus_id', 'create_time', 'active'];
     
}
