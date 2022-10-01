<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoOrders extends Model
{
    //Database Table
    public $table = 'promo_orders';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
