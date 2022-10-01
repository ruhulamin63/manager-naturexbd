<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    //Database Table
    public $table = 'grocery_leads';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
