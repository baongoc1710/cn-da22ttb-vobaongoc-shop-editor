@extends('layouts.app')

@section('title', 'Trang Chủ - MyTee Studio')

@section('content')

{{-- 1. HERO BANNER --}}
<section class="hero is-medium is-link" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="hero-body">
        <div class="container has-text-centered">
            <p class="title is-1 is-size-2-mobile" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                Thiết Kế Chiếc Áo Của Riêng Bạn
            </p>
            <p class="subtitle is-4 is-size-5-mobile mt-3">
                Thỏa sức sáng tạo - Chất lượng cao cấp - In ấn sắc nét
            </p>
            <a href="#products" class="button is-warning is-large is-rounded mt-4 shadow-lg">
                <span class="icon"><i class="fas fa-magic"></i></span>
                <span>Bắt đầu ngay</span>
            </a>
        </div>
    </div>
</section>

{{-- 2. QUY TRÌNH 3 BƯỚC --}}
<section class="section has-background-white-ter">
    <div class="container">
        <div class="columns has-text-centered">
            <div class="column">
                <div class="box h-100" style="background: transparent; box-shadow: none;">
                    <span class="icon is-large has-text-link mb-3">
                        <i class="fas fa-tshirt fa-3x"></i>
                    </span>
                    <h3 class="title is-5">1. Chọn Sản Phẩm</h3>
                    <p>Chọn mẫu áo, màu sắc và size phù hợp với bạn.</p>
                </div>
            </div>
            <div class="column">
                <div class="box h-100" style="background: transparent; box-shadow: none;">
                    <span class="icon is-large has-text-primary mb-3">
                        <i class="fas fa-paint-brush fa-3x"></i>
                    </span>
                    <h3 class="title is-5">2. Tự Do Thiết Kế</h3>
                    <p>Thêm hình ảnh, chữ, logo bằng công cụ online.</p>
                </div>
            </div>
            <div class="column">
                <div class="box h-100" style="background: transparent; box-shadow: none;">
                    <span class="icon is-large has-text-success mb-3">
                        <i class="fas fa-shipping-fast fa-3x"></i>
                    </span>
                    <h3 class="title is-5">3. Nhận Hàng</h3>
                    <p>Chúng tôi in và giao hàng tận nơi cho bạn.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. DANH SÁCH SẢN PHẨM (MAIN SECTION) --}}
<section class="section" id="products">
    <div class="container">
        <div class="has-text-centered mb-6">
            <h2 class="title is-2 has-text-dark">Sản Phẩm Nổi Bật</h2>
            <div class="block">
                <span class="icon is-small has-text-primary"><i class="fas fa-star"></i></span>
                <span class="subtitle is-6 has-text-grey">Chọn một mẫu để bắt đầu thiết kế</span>
                <span class="icon is-small has-text-primary"><i class="fas fa-star"></i></span>
            </div>
        </div>

       <div class="columns is-multiline">
            @foreach($products as $product)
                <div class="column is-3-desktop is-6-tablet">
                    <div class="card product-card">
                        
                        {{-- Nhãn Category (Nếu bảng products có cột category_name hoặc type) --}}
                        {{-- <div class="card-badge">Mới</div> --}}

                        {{-- Hình ảnh --}}
                        <div class="card-image">
                            <figure class="image is-4by3">
                                {{-- Link ảnh lấy từ logic Controller --}}
                                <img src="{{ $product->display_image ? asset($product->display_image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" 
                                     alt="{{ $product->name }}" 
                                     style="object-fit: contain; padding: 10px;">
                            </figure>
                        </div>

                        {{-- Nội dung --}}
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content">
                                    <p class="title is-6 mb-2" style="height: 48px; overflow: hidden;">
                                        {{ $product->name }}
                                    </p>
                                    {{-- Giá tiền (giả sử cột price trong bảng product) --}}
                                    <p class="subtitle is-6 has-text-danger has-text-weight-bold">
                                        {{ number_format($product->base_price ?? 0, 0, ',', '.') }}₫
                                    </p>
                                </div>
                            </div>

                            <div class="content is-small">
                                <span class="icon has-text-warning"><i class="fas fa-palette"></i></span>
                                <span>{{ $product->colors_count }} màu sắc</span>
                            </div>

                            {{-- Nút hành động --}}
                            <div class="buttons mt-4">
                                {{-- Truyền ID sản phẩm và ID màu ngẫu nhiên sang trang thiết kế --}}
                                <a href="{{ route('design') }}?product_id={{ $product->id }}&color_id={{ $product->random_color_id }}" 
                                   class="button is-primary is-fullwidth is-outlined hover-solid">
                                    <span class="icon"><i class="fas fa-pencil-alt"></i></span>
                                    <span>Thiết kế ngay</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Nút xem thêm --}}
        <div class="has-text-centered mt-6">
            <a href="#" class="button is-rounded">Xem tất cả sản phẩm</a>
        </div>
    </div>
</section>

{{-- CSS TÙY CHỈNH CHO TRANG CHỦ --}}
<style>
    /* Hiệu ứng card sản phẩm */
    .product-card {
        border-radius: 8px;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #3273dc;
    }

    /* Badge trên góc ảnh */
    .card-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ff3860;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Nút hover đổi màu */
    .hover-solid:hover {
        background-color: #00d1b2 !important;
        color: white !important;
        border-color: transparent !important;
    }
    
    .shadow-lg {
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
</style>
@endsection