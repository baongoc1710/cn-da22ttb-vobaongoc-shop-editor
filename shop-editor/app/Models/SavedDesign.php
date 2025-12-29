<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedDesign extends Model
{
    protected $fillable = [
        'user_id',
        'product_color_id',
        'design_name',
        'front_design_json',
        'back_design_json',
        'front_preview_img',
        'back_preview_img'
    ];

    // QUAN TRỌNG: Tự động chuyển JSON trong DB thành Array trong PHP và ngược lại
    protected $casts = [
        'front_design_json' => 'array',
        'back_design_json'  => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function productColor() {
        return $this->belongsTo(ProductColor::class);
    }
}