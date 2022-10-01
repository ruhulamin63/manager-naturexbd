<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class AccessList extends Model
{
    //Database Table
    public $table = 'grocery_access_list';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
