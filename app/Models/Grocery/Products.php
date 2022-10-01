<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //Database Table
    public $table = 'grocery_products';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
