<?php

namespace App\Restaurant;

use Illuminate\Database\Eloquent\Model;

class resOrder extends Model
{
    //Database Table
    public $table = 'restaurant_orders';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
