<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //Database Table
    public $table = 'kt_app_notification';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
