<?php

namespace App\Model\backend;

use Illuminate\Database\Eloquent\Model;

class ChangePassword extends Model {

    protected $table = 'admins';
    protected $fillable = ['name', 'password'];

}
