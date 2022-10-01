<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class AccountGroups extends Model
{
    //Database Table
    public $table = 'grocery_account_groups';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
