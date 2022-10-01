<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkashRefunds extends Model
{
    //Database Table
    public $table = 'bkash_refunds';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
