<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RestaurantProperty extends Model
{
    //Database Table
    public $table = 'kt_restaurant_property';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
