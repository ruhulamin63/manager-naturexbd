<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bKashHistory extends Model
{
    //Database Table
    public $table = 'bkash_history';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
