<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RestaurantCategory extends Model
{
    //Database Table
    public $table = 'kt_restaurants_category';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
