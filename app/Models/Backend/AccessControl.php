<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class AccessControl extends Model
{
    //Database Table
    public $table = 'kt_backend_access_control';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
