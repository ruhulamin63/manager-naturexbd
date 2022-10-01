<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RestaurantManager extends Model
{
    //Database Table
    public $table = 'kt_restaurants_list';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
