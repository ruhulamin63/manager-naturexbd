<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class FeaturedRestaurantManager extends Model
{
    //Database Table
    public $table = 'kt_restaurants_featured';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
