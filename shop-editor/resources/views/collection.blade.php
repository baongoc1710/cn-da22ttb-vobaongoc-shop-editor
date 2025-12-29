@extends('layouts.app')

@section('title', 'Bộ sưu tập của tôi')

@section('content')
    <section class="section" style="background-color: #f5f5f5; min-height: 90vh;">
        <div class="container">

            <div class="level">
                <div class="level-left">
                    <h1 class="title is-3">Bộ sưu tập của tôi</h1>
                </div>
                <div class="level-right">
                    <a href="{{ route('design') }}" class="button is-primary">
                        <span class="icon"><i class="fas fa-plus"></i></span>
                        <span>Tạo thiết kế mới</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="notification is-success is-light">{{ session('success') }}</div>
            @endif

            @if ($designs->count() > 0)
                <div class="columns is-multiline">
                    @foreach ($designs as $design)
                        <div class="column is-4-desktop is-6-tablet">
                            <div class="card shadow-hover" style="height: 100%; border-radius: 8px; transition: 0.3s;">
                                <div class="card-image"
                                    style="padding: 10px; background: #fff; border-bottom: 1px solid #eee;">
                                    <div class="columns is-mobile is-gapless mb-0">
                                        {{-- Mặt trước --}}
                                        <div class="column is-6 has-text-centered">
                                            <figure class="image is-square">
                                                <img src="{{ asset($design->front_preview_img) }}"
                                                    style="object-fit: contain;">
                                            </figure>
                                            <span class="tag is-light is-small mt-1">Trước</span>
                                        </div>
                                        {{-- Mặt sau --}}
                                        @if ($design->back_preview_img)
                                            <div class="column is-6 has-text-centered">
                                                <figure class="image is-square">
                                                    <img src="{{ asset($design->back_preview_img) }}"
                                                        style="object-fit: contain;">
                                                </figure>
                                                <span class="tag is-light is-small mt-1">Sau</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-content">
                                    <p class="title is-5 mb-2">{{ $design->design_name }}</p>
                                    <p class="subtitle is-7 has-text-grey">
                                        Tạo ngày: {{ $design->created_at->format('d/m/Y') }}
                                    </p>

                                    <div class="buttons is-right mt-4">
                                        {{-- Nút Xóa --}}
                                        <form action="{{ route('collection.destroy', $design->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                            @csrf @method('DELETE')
                                            <button class="button is-small is-danger is-outlined is-rounded">
                                                <span class="icon"><i class="fas fa-trash"></i></span>
                                            </button>
                                        </form>

                                        {{-- Nút Đặt hàng (Mở Modal) --}}
                                        <button class="button is-small is-success is-rounded open-cart-modal"
                                            data-id="{{ $design->id }}" data-name="{{ $design->design_name }}">
                                            <span class="icon"><i class="fas fa-cart-plus"></i></span>
                                            <span>Đặt hàng</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                <div class="mt-5">
                    {{ $designs->links('pagination.bulma') }}
                </div>
            @else
                <div class="has-text-centered mt-6">
                    <p class="title is-5 has-text-grey">Bạn chưa lưu thiết kế nào.</p>
                    <a href="{{ route('design') }}" class="button is-link is-outlined mt-3">Thiết kế ngay</a>
                </div>
            @endif
        </div>

        {{-- MODAL CHỌN SIZE & SỐ LƯỢNG --}}
        <div class="modal" id="quickAddCartModal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Thêm vào giỏ hàng</p>
                    <button class="delete close-modal" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <p class="mb-4">Bạn đang chọn: <strong id="modalDesignName"></strong></p>

                    <div class="field">
                        <label class="label">Chọn Size</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="modalSize">
                                    <option value="S">Size S</option>
                                    <option value="M" selected>Size M</option>
                                    <option value="L">Size L</option>
                                    <option value="XL">Size XL</option>
                                    <option value="XXL">Size XXL</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Số lượng</label>
                        <div class="control">
                            <input class="input" type="number" id="modalQty" value="1" min="1">
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" id="confirmAddToCart">Thêm ngay</button>
                    <button class="button close-modal">Hủy</button>
                </footer>
            </div>
        </div>
    </section>

    {{-- SCRIPT XỬ LÝ MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('quickAddCartModal');
            let selectedDesignId = null;

            // Mở Modal
            document.querySelectorAll('.open-cart-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedDesignId = btn.dataset.id;
                    document.getElementById('modalDesignName').innerText = btn.dataset.name;
                    modal.classList.add('is-active');
                });
            });

            // Đóng Modal
            document.querySelectorAll('.close-modal, .modal-background').forEach(el => {
                el.addEventListener('click', () => modal.classList.remove('is-active'));
            });

            // Xác nhận thêm vào giỏ
            document.getElementById('confirmAddToCart').addEventListener('click', function() {
                const btn = this;
                btn.classList.add('is-loading');

                const size = document.getElementById('modalSize').value;
                const qty = parseInt(document.getElementById('modalQty').value) || 1;

                // Gọi Ajax thêm vào giỏ
                $.post("{{ route('cart.add') }}", {
                        _token: "{{ csrf_token() }}",
                        design_id: selectedDesignId, // Biến này lấy từ lúc mở modal
                        size: size,
                        quantity: qty
                    })
                    .done(function(response) {
                        alert('Đã thêm vào giỏ hàng thành công!');

                        // Đóng Modal
                        const modal = document.getElementById('quickAddCartModal');
                        if (modal) modal.classList.remove('is-active');

                        // --- CẬP NHẬT SỐ LƯỢNG TRÊN NAVBAR ---

                        // Cách 1: Cập nhật dựa trên số liệu chính xác từ Server trả về (Khuyên dùng)
                        // Yêu cầu Controller phải trả về 'cart_count'
                        if (response.cart_count !== undefined) {
                            $("#navCartCount").text(response.cart_count);
                        }
                        // Cách 2: Cộng dồn thủ công (Nhanh, không cần sửa Controller, nhưng dễ sai lệch)
                        else {
                            const cartCountElem = document.getElementById(
                            'navCartCount'); // <--- SỬA ID Ở ĐÂY CHO KHỚP HTML
                            if (cartCountElem) {
                                // Logic: Nếu đếm tổng số áo
                                // let currentCount = parseInt(cartCountElem.innerText) || 0;
                                // cartCountElem.innerText = currentCount + qty;

                                // Logic: Nếu đếm số dòng sản phẩm (thường dùng hơn)
                                let currentCount = parseInt(cartCountElem.innerText) || 0;
                                cartCountElem.innerText = currentCount + 1;
                            }
                        }

                        // Hiệu ứng nháy để người dùng thấy thay đổi
                        $("#navCartCount").fadeOut(100).fadeIn(100);
                    })
                    .fail(function(xhr) {
                        console.log(xhr.responseText);
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                    })
                    .always(function() {
                        btn.classList.remove('is-loading');
                    });
            });
        });
    </script>

    <style>
        .shadow-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
