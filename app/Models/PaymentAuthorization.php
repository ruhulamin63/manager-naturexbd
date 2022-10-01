<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAuthorization extends Model
{
    //Database Table
    public $table = 'payment_authorization';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
