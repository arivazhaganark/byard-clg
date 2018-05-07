<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cms_Model extends Model
{
    //
    protected $table='cms';
    protected $fillable=['title','slug','menu_id','content','page_type','page_linktype','atnet_title','atnet_description','atnet_keywords','status','position','page_link'];
}
