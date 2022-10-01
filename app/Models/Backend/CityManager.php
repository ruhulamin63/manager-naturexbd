<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class CityManager extends Model
{
    //Database Table
    public $table = 'kt_city_list';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
