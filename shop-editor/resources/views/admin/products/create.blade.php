@extends('layouts.app')
@section('title', 'Thêm sản phẩm mới')
@section('content')
    <section class="section has-background-white-ter">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <h1 class="title is-4">Thêm sản phẩm mới</h1>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.products.index') }}" class="button">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span>Quay lại</span>
                    </a>
                </div>
            </div>

            <div class="columns">
                <div class="column is-10 is-offset-1">
                    <div class="box">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- PHẦN 1: THÔNG TIN SẢN PHẨM --}}
                            <h3 class="title is-5 has-text-link mb-4">
                                <span class="icon"><i class="fas fa-info-circle"></i></span>
                                Thông tin sản phẩm
                            </h3>

                            <div class="field">
                                <label class="label">Tên sản phẩm <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input class="input" type="text" name="name" placeholder="Ví dụ: Áo Thun Cotton Basic"
                                        value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Giá nhập (VNĐ) <span class="has-text-danger">*</span></label>
                                        <div class="control has-icons-left">
                                            <input class="input" type="number" name="import_price" placeholder="80000"
                                                value="{{ old('import_price') }}" required>
                                            <span class="icon is-small is-left"><i class="fas fa-file-invoice-dollar"></i></span>
                                        </div>
                                        <p class="help">Giá vốn nhập hàng</p>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Giá bán (VNĐ) <span class="has-text-danger">*</span></label>
                                        <div class="control has-icons-left">
                                            <input class="input" type="number" name="base_price" placeholder="190000"
                                                value="{{ old('base_price') }}" required>
                                            <span class="icon is-small is-left"><i class="fas fa-tag"></i></span>
                                        </div>
                                        <p class="help">Giá bán cho khách</p>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Mô tả</label>
                                <div class="control">
                                    <textarea class="textarea" name="description" rows="3" 
                                        placeholder="Chất liệu cotton 100%, co giãn 4 chiều, thoáng mát...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <hr class="my-5">

                            {{-- PHẦN 2: THÔNG TIN MÀU ĐẦU TIÊN (TÙY CHỌN) --}}
                            <h3 class="title is-5 has-text-primary mb-4">
                                <span class="icon"><i class="fas fa-palette"></i></span>
                                Thêm màu đầu tiên (Tùy chọn)
                            </h3>
                            
                            <div class="notification is-info is-light">
                                <p class="is-size-7">
                                    <strong>Lưu ý:</strong> Bạn có thể bỏ qua phần này và thêm màu sau khi tạo sản phẩm. 
                                    Hoặc thêm ngay màu đầu tiên để sản phẩm hiển thị đẹp hơn trên trang chủ.
                                </p>
                            </div>

                            <div class="columns">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Tên màu</label>
                                        <div class="control">
                                            <input class="input" type="text" name="first_color_name" 
                                                placeholder="Ví dụ: Màu Trắng" value="{{ old('first_color_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Mã màu</label>
                                        <div class="field has-addons">
                                            <div class="control">
                                                <input class="input" type="color" id="colorPicker" value="#ffffff"
                                                    style="height: 40px; width: 60px; padding: 2px;"
                                                    onchange="document.getElementById('hexCode').value = this.value">
                                            </div>
                                            <div class="control is-expanded">
                                                <input class="input" type="text" name="first_hex_code" id="hexCode"
                                                    placeholder="#FFFFFF" value="{{ old('first_hex_code', '#ffffff') }}"
                                                    onchange="document.getElementById('colorPicker').value = this.value">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Ảnh mặt trước</label>
                                        <div class="file has-name is-fullwidth">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="first_image_front" accept="image/*"
                                                    onchange="previewImage(this, 'previewFront', 'frontName')">
                                                <span class="file-cta">
                                                    <span class="file-icon"><i class="fas fa-upload"></i></span>
                                                    <span class="file-label">Chọn ảnh...</span>
                                                </span>
                                                <span class="file-name" id="frontName">Chưa chọn</span>
                                            </label>
                                        </div>
                                        <figure class="image is-square mt-3" id="previewFrontBox" style="display:none; border: 1px dashed #ccc;">
                                            <img id="previewFront" src="" style="object-fit: contain;">
                                        </figure>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Ảnh mặt sau</label>
                                        <div class="file has-name is-fullwidth">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="first_image_back" accept="image/*"
                                                    onchange="previewImage(this, 'previewBack', 'backName')">
                                                <span class="file-cta">
                                                    <span class="file-icon"><i class="fas fa-upload"></i></span>
                                                    <span class="file-label">Chọn ảnh...</span>
                                                </span>
                                                <span class="file-name" id="backName">Chưa chọn</span>
                                            </label>
                                        </div>
                                        <figure class="image is-square mt-3" id="previewBackBox" style="display:none; border: 1px dashed #ccc;">
                                            <img id="previewBack" src="" style="object-fit: contain;">
                                        </figure>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5">

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary is-large is-fullwidth">
                                        <span class="icon"><i class="fas fa-save"></i></span>
                                        <span>Lưu sản phẩm</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function previewImage(input, imgId, nameId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(imgId).src = e.target.result;
                    document.getElementById(imgId + 'Box').style.display = 'block';
                }
                reader.readAsDataURL(file);
                document.getElementById(nameId).textContent = file.name;
            }
        }
    </script>
@endsection
