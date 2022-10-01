<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangoOrder extends Model
{
    //Database Table
    public $table = 'kt_mango_orders';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
