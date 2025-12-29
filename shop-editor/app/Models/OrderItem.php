<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'saved_design_id',
        'size',
        'quantity',
        'unit_price', // Giá bán
        'unit_cost',  // Giá vốn
        'print_cost'  // Chi phí in
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function savedDesign() {
        return $this->belongsTo(SavedDesign::class);
    }
}