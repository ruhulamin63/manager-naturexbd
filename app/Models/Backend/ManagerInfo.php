<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class ManagerInfo extends Model
{
    //Database Table
    public $table = 'kt_backend_manager_info';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
