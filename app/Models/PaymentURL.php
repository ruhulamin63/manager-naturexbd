<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentURL extends Model
{
    //Database Table
    public $table = 'payment_url';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
