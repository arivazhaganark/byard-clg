<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class CdnCustomerKeyMapModel extends Model
{
     
    public $timestamps = false;
 
    protected $table='cdn_customer_key_mapping';

    protected $fillable=['key_map_id', 'cus_key_id', 'lin_id', 'package_max_cnt','active','package_key'];
     
}
