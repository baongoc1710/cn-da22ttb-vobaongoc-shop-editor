<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductColor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'color_name', 'hex_code', 'image_front', 'image_back'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}