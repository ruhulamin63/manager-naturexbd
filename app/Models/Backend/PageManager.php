<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class PageManager extends Model
{
    //Database Table
    public $table = 'kt_backend_page_manager';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
