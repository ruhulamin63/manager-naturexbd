<?php

namespace App\Restaurant;

use Illuminate\Database\Eloquent\Model;

class resProducts extends Model
{
    //Database Table
    public $table = 'restaurant_products';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
