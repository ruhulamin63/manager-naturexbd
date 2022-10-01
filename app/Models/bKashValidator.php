<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bKashValidator extends Model
{
    //Database Table
    public $table = 'bkash_invoice_id';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
