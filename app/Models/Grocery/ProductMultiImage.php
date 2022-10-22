<?php

namespace App\Models\grocery;

use Illuminate\Database\Eloquent\Model;

class ProductMultiImage extends Model
{
    protected $table = 'grocery_products_image_file';
    protected $fillable = [
        'product_id',
        'image_path',
        'status',
    ];
}
