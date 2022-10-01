<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //Database Table
    public $table = 'grocery_users';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
