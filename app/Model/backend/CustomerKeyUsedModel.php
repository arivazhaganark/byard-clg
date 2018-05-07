<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class CustomerKeyUsedModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='atnetwork_customer_key_used';

    protected $fillable=[ 'ck_id', 'cus_id', 'lin_id'];

     
}
