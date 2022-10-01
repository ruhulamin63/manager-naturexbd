<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //Database Table
    public $table = 'grocery_orders';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
