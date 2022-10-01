<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class APIManager extends Model
{
    //Database Table
    public $table = 'kt_api_manager';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
