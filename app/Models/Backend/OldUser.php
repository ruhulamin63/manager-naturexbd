<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class OldUser extends Model
{
    //Database Table
    public $table = 'kt_app_old_users';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
