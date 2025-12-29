<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clipart extends Model
{
    protected $fillable = ['name', 'image_url', 'category'];
}