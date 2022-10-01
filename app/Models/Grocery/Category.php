<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //Database Table
    public $table = 'grocery_category';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
