<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class OrderRaw extends Model
{
    //Database Table
    public $table = 'grocery_orders_raw';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
