<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangoLeads extends Model
{
    //Database Table
    public $table = 'mango_leads';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
