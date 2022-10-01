<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRaw extends Model
{
    //Database Table
    public $table = 'kt_order_raw';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
