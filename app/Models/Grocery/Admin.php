<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //Database Table
    public $table = 'grocery_admin';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
