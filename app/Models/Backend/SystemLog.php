<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    //Database Table
    public $table = 'kt_backend_system_log';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
