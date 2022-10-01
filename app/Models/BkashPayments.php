<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkashPayments extends Model
{
    //Database Table
    public $table = 'bkash_payments';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
