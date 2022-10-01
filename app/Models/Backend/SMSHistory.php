<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class SMSHistory extends Model
{
    //Database Table
    public $table = 'kt_sms_history';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
