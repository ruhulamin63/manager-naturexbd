<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //Database Table
    public $table = 'grocery_city';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
