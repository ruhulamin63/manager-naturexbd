<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class ManagerAuth extends Model
{
    //Database Table
    public $table = 'kt_backend_manager_auth';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
