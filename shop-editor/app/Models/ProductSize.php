<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = ['size_name', 'price_modifier'];
    // Bảng này thường dùng để lookup (tra cứu) nên ít khi cần relationship phức tạp
}