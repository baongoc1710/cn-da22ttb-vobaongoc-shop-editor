@extends('layouts.app')
@section('title', 'Thông tin cá nhân')
@section('content')
    <section class="section has-background-white-ter">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <h1 class="title is-4">Chỉnh sửa sản phẩm: {{ $product->name }}</h1>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.products.index') }}" class="button">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span>Quay lại</span>
                    </a>
                </div>
            </div>

            <div class="columns is-variable is-6">
                <div class="column is-4">
                    <div class="box h-100">
                        <h3 class="title is-5 mb-4 has-text-link">1. Thông tin chung</h3>

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="field">
                                <label class="label">Tên sản phẩm</label>
                                <div class="control">
                                    <input class="input" type="text" name="name" value="{{ $product->name }}"
                                        required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Giá gốc (VNĐ)</label>
                                <div class="control">
                                    <input class="input" type="number" name="import_price"
                                        value="{{ $product->import_price }}" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Giá bán (VNĐ)</label>
                                <div class="control">
                                    <input class="input" type="number" name="base_price"
                                        value="{{ $product->base_price }}" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Danh mục</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="category">
                                            <option value="T-Shirt" {{ $product->category == 'T-Shirt' ? 'selected' : '' }}>
                                                Áo Thun</option>
                                            <option value="Hoodie" {{ $product->category == 'Hoodie' ? 'selected' : '' }}>
                                                Hoodie</option>
                                            <option value="Bag" {{ $product->category == 'Bag' ? 'selected' : '' }}>Túi
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Mô tả</label>
                                <div class="control">
                                    <textarea class="textarea" name="description" rows="3">{{ $product->description }}</textarea>
                                </div>
                            </div>

                            <button class="button is-link is-fullwidth mt-4">Cập nhật thông tin</button>
                        </form>
                    </div>
                </div>

                <div class="column is-8">
                    <div class="box h-100">
                        <div class="level mb-4">
                            <div class="level-left">
                                <h3 class="title is-5 has-text-primary">2. Danh sách màu & Ảnh</h3>
                            </div>
                            <div class="level-right">
                                <button class="button is-primary" onclick="openAddModal()">
                                    <span class="icon"><i class="fas fa-plus"></i></span>
                                    <span>Thêm màu mới</span>
                                </button>
                            </div>
                        </div>

                        <table class="table is-fullwidth is-bordered is-hoverable" style="vertical-align: middle">
                            <thead>
                                <tr class="has-background-light">
                                    <th>Tên màu</th>
                                    <th class="has-text-centered">Mặt trước</th>
                                    <th class="has-text-centered">Mặt sau</th>
                                    <th class="has-text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->colors as $color)
                                    <tr>
                                        <td style="vertical-align: middle">
                                            <div style="display: flex; align-items: center;">
                                                <span
                                                    style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $color->hex_code }}; border: 1px solid #ccc; margin-right: 8px;"></span>
                                                <strong>{{ $color->color_name }}</strong>
                                            </div>
                                        </td>
                                        <td class="has-text-centered">
                                            <figure class="image is-64x64 is-inline-block">
                                                <img src="{{ asset($color->image_front) }}"
                                                    style="border:1px solid #ddd; border-radius:4px; object-fit: contain;">
                                            </figure>
                                        </td>
                                        <td class="has-text-centered">
                                            @if ($color->image_back)
                                                <figure class="image is-64x64 is-inline-block">
                                                    <img src="{{ asset($color->image_back) }}"
                                                        style="border:1px solid #ddd; border-radius:4px; object-fit: contain;">
                                                </figure>
                                            @else
                                                <span class="has-text-grey is-size-7">Không có</span>
                                            @endif
                                        </td>
                                        <td class="has-text-right" style="vertical-align: middle">
                                            <button class="button is-small is-info is-outlined"
                                                onclick="openEditModal(
                                        '{{ $color->id }}', 
                                        '{{ $color->color_name }}', 
                                        '{{ $color->hex_code }}', 
                                        '{{ asset($color->image_front) }}', 
                                        '{{ $color->image_back ? asset($color->image_back) : '' }}'
                                    )">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.products.colors.destroy', $color->id) }}"
                                                method="POST" style="display:inline-block"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa màu này không?');">
                                                @csrf @method('DELETE')
                                                <button class="button is-small is-danger is-outlined"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="has-text-centered py-5 has-text-grey">
                                            Chưa có màu nào. Hãy bấm "Thêm màu mới".
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal" id="colorModal">
                <div class="modal-background" onclick="closeModal()"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title" id="modalTitle">Thêm màu mới</p>
                        <button class="delete" aria-label="close" onclick="closeModal()"></button>
                    </header>

                    <form id="colorForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <section class="modal-card-body">
                            <div class="columns">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Tên màu sắc</label>
                                        <div class="control">
                                            <input class="input" type="text" name="color_name" id="modalColorName"
                                                placeholder="Ví dụ: Đỏ đô..." required>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Mã màu hiển thị</label>
                                        <div class="field has-addons">
                                            <div class="control">
                                                <input class="input" type="color" id="modalColorPicker"
                                                    style="height: 40px; width: 60px; padding: 2px;"
                                                    onchange="updateHexInput(this.value)">
                                            </div>
                                            <div class="control is-expanded">
                                                <input class="input" type="text" name="hex_code" id="modalHexCode"
                                                    placeholder="#FFFFFF" required
                                                    onchange="updateColorPicker(this.value)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="columns">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Ảnh mặt trước (*)</label>
                                        <div class="file is-small has-name is-fullwidth">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="image_front"
                                                    accept="image/*"
                                                    onchange="previewImage(this, 'previewFrontImg', 'previewFrontBox', 'frontFileName')">
                                                <span class="file-cta">
                                                    <span class="file-icon"><i class="fas fa-upload"></i></span>
                                                    <span class="file-label">Chọn ảnh...</span>
                                                </span>
                                                <span class="file-name" id="frontFileName">Chưa chọn</span>
                                            </label>
                                        </div>
                                        <figure class="image is-square mt-2 has-background-light" id="previewFrontBox"
                                            style="display:none; border: 1px dashed #ccc;">
                                            <img id="previewFrontImg" src="" style="object-fit: contain;">
                                            <p class="has-text-centered is-size-7 mt-1 has-text-grey">Ảnh mặt trước</p>
                                        </figure>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Ảnh mặt sau (Tùy chọn)</label>
                                        <div class="file is-small has-name is-fullwidth">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="image_back"
                                                    accept="image/*"
                                                    onchange="previewImage(this, 'previewBackImg', 'previewBackBox', 'backFileName')">
                                                <span class="file-cta">
                                                    <span class="file-icon"><i class="fas fa-upload"></i></span>
                                                    <span class="file-label">Chọn ảnh...</span>
                                                </span>
                                                <span class="file-name" id="backFileName">Chưa chọn</span>
                                            </label>
                                        </div>
                                        <figure class="image is-square mt-2 has-background-light" id="previewBackBox"
                                            style="display:none; border: 1px dashed #ccc;">
                                            <img id="previewBackImg" src="" style="object-fit: contain;">
                                            <p class="has-text-centered is-size-7 mt-1 has-text-grey">Ảnh mặt sau</p>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <footer class="modal-card-foot" style="justify-content: flex-end">
                            <button type="button" class="button" onclick="closeModal()">Hủy</button>
                            <button type="submit" class="button is-primary" id="modalSubmitBtn">Lưu lại</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        const modal = document.getElementById('colorModal');
        const form = document.getElementById('colorForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('modalSubmitBtn');
        const methodInput = document.getElementById('formMethod');
        const storeUrl = "{{ route('admin.products.colors.store', $product->id) }}";

        // Đồng bộ Color Picker
        function updateHexInput(val) {
            document.getElementById('modalHexCode').value = val;
        }

        function updateColorPicker(val) {
            document.getElementById('modalColorPicker').value = val;
        }

        // === HÀM QUAN TRỌNG: XEM TRƯỚC ẢNH KHI UPLOAD ===
        function previewImage(input, imgId, boxId, nameId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Gán dữ liệu ảnh vào thẻ img
                    document.getElementById(imgId).src = e.target.result;
                    // Hiển thị khung chứa ảnh
                    document.getElementById(boxId).style.display = 'block';
                }

                // Đọc file
                reader.readAsDataURL(input.files[0]);

                // Cập nhật tên file hiển thị
                document.getElementById(nameId).textContent = input.files[0].name;
            }
        }

        // Mở Modal THÊM MỚI
        function openAddModal() {
            modal.classList.add('is-active');
            title.innerText = "Thêm màu mới";
            btn.innerText = "Thêm ngay";

            form.reset();
            form.action = storeUrl;
            methodInput.value = "POST";

            document.getElementById('modalHexCode').value = "#ffffff";
            document.getElementById('modalColorPicker').value = "#ffffff";

            // Reset ảnh preview và tên file
            document.getElementById('previewFrontBox').style.display = 'none';
            document.getElementById('previewBackBox').style.display = 'none';
            document.getElementById('previewFrontImg').src = "";
            document.getElementById('previewBackImg').src = "";
            document.getElementById('frontFileName').textContent = "Chưa chọn";
            document.getElementById('backFileName').textContent = "Chưa chọn";
        }

        // Mở Modal CHỈNH SỬA
        function openEditModal(id, name, hex, frontUrl, backUrl) {
            modal.classList.add('is-active');
            title.innerText = "Cập nhật màu: " + name;
            btn.innerText = "Cập nhật";

            document.getElementById('modalColorName').value = name;
            document.getElementById('modalHexCode').value = hex || "#ffffff";
            document.getElementById('modalColorPicker').value = hex || "#ffffff";

            let updateUrl = "{{ route('admin.products.colors.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', id);

            form.action = updateUrl;
            methodInput.value = "PUT";

            // Hiển thị ảnh cũ từ server
            document.getElementById('previewFrontBox').style.display = 'block';
            document.getElementById('previewFrontImg').src = frontUrl;
            document.getElementById('frontFileName').textContent = "Ảnh hiện tại";

            if (backUrl) {
                document.getElementById('previewBackBox').style.display = 'block';
                document.getElementById('previewBackImg').src = backUrl;
                document.getElementById('backFileName').textContent = "Ảnh hiện tại";
            } else {
                document.getElementById('previewBackBox').style.display = 'none';
                document.getElementById('backFileName').textContent = "Chưa chọn";
            }
        }

        function closeModal() {
            modal.classList.remove('is-active');
        }
    </script>

@endsection
