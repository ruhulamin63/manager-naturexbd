<?php

namespace App\Models\grocery;

use Illuminate\Database\Eloquent\Model;

class ProductMultiImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'status',
    ];
}
