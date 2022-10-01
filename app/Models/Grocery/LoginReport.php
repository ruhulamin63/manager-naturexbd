<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class LoginReport extends Model
{
    //Database Table
    public $table = 'grocery_login_report';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
