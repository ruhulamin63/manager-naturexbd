<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class CityPreview extends Model
{
    //Database Table
    public $table = 'grocery_city_preview';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
