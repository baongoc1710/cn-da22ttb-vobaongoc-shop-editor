<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'description', 'base_price', 'import_price', 'is_active'];

    // 1. Khai báo mối quan hệ với bảng Colors
    public function colors()
    {
        return $this->hasMany(ProductColor::class, 'product_id');
    }

    // 2. Tạo thuộc tính ảo để lấy 1 ảnh ngẫu nhiên
    // Khi gọi $product->random_image, nó sẽ tự chạy hàm này
    public function getRandomImageAttribute()
    {
        // Kiểm tra xem sản phẩm có màu nào không
        if ($this->colors->isNotEmpty()) {
            // Lấy ngẫu nhiên 1 màu trong danh sách màu của sản phẩm này
            return $this->colors->random()->image_front;
        }

        return null; // Trả về null nếu chưa có màu nào
    }
}
