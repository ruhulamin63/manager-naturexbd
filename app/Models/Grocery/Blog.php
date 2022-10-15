<?php

namespace App\Models\Grocery;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $table = 'blogs';
    protected $fillable = ['title', 'slug', 'description', 'image_path', 'video_path', 'status'];
}
