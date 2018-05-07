<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='atnetwork_customer_master';

    protected $fillable=['cus_id','c_name','c_id','email_id','create_time','active'];
     
}
