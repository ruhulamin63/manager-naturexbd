<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public $table = 'offers';
    protected $fillable = [
        'offer_name',
        'meta_keyword',
        'description',
        'image_path',
        'status'
    ];
}
