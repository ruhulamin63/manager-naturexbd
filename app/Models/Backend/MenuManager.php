<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class MenuManager extends Model
{
    //Database Table
    public $table = 'kt_restaurants_menu';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
