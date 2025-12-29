@extends('layouts.app')

@section('title', 'Thiết Kế Áo - MyTee Studio')

@section('content')
    <style>
        /* === 1. KHUNG BAO NGOÀI (Wrapper) === */
        .ds-slider-container {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            /* Màu nền xám nhẹ tách biệt */
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 5px;
            position: relative;
            gap: 5px;
            /* Khoảng cách giữa nút và danh sách */
        }

        /* === 2. DANH SÁCH ẢNH (Track) === */
        .ds-slider-track {
            flex-grow: 1;
            /* Chiếm hết không gian còn lại */
            display: flex;
            /* Xếp ngang */
            align-items: center;
            /* Căn giữa dọc */

            overflow-x: auto;
            /* Cho phép cuộn */
            scroll-behavior: smooth;
            /* Cuộn mượt */

            /* Cố định chiều cao để không bị nhảy layout */
            height: 110px;

            /* Ẩn thanh cuộn (Scrollbar) */
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        .ds-slider-track::-webkit-scrollbar {
            display: none;
            /* Chrome/Safari */
        }

        /* === 3. TỪNG ẢNH ÁO (Item) === */
        .ds-shirt-item {
            /* Kích thước cố định, QUAN TRỌNG: flex-shrink: 0 để không bị bóp méo */
            flex: 0 0 100px;
            width: 100px;
            height: 100px;

            object-fit: contain;
            /* Ảnh luôn hiển thị trọn vẹn */
            margin: 0 5px;
            /* Khoảng cách giữa các ảnh */

            border: 2px solid transparent;
            /* Viền chờ sẵn */
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
            /* Nền trắng cho ảnh nổi bật */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Hiệu ứng khi di chuột hoặc đang chọn */
        .ds-shirt-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .ds-shirt-item.active {
            border-color: #3273dc;
            /* Viền xanh khi chọn */
            box-shadow: 0 0 0 2px rgba(50, 115, 220, 0.2);
        }

        /* === 4. NÚT BẤM 2 BÊN (Nav Buttons) === */
        .ds-nav-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #dbdbdb;
            background: #fff;
            color: #555;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
            /* Không bị bóp méo nút */
            z-index: 2;
        }

        .ds-nav-btn:hover {
            background-color: #3273dc;
            color: white;
            border-color: #3273dc;
        }
    </style>
    <section class="section" id="designer">
        <div class="container">
            <div class="columns">

                <div class="column is-3">

                    <div class="card">
                        <div class="card-content">
                            <p class="title is-6">Công cụ thiết kế</p>
                            <div class="buttons designer-toolbar">
                                <a class="button is-small is-light requires-garment" id="addImageModalDialog"
                                    title="Tải ảnh từ máy">
                                    <span class="icon is-small"><i class="fa fa-upload"></i></span>
                                    <span>Upload</span>
                                </a>
                                <a class="button is-small is-light requires-garment" id="galleryDialog"
                                    title="Chọn clipart">
                                    <span class="icon is-small"><i class="fa fa-image"></i></span>
                                    <span>Clipart</span>
                                </a>
                                <a class="button is-small is-light requires-garment" id="addText" title="Thêm chữ">
                                    <span class="icon is-small"><i class="fa fa-text-height"></i></span>
                                    <span>Text</span>
                                </a>
                                <a class="button is-small is-light requires-garment" id="drawPolygon"
                                    title="Vẽ vùng in (Clipping)">
                                    <span class="icon is-small"><i class="fa fa-draw-polygon"></i></span>
                                    <span>Vùng in</span>
                                </a>
                                <a class="button is-small is-light requires-garment" id="removeSelectedObject"
                                    title="Xoá đối tượng">
                                    <span class="icon is-small"><i class="fa fa-trash"></i></span>
                                    <span>Xoá</span>
                                </a>
                                <a class="button is-small is-light requires-garment" id="clearViewport" title="Xoá tất cả">
                                    <span class="icon is-small"><i class="fa fa-broom"></i></span>
                                    <span>Reset</span>
                                </a>
                            </div>

                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 8px; flex-wrap: wrap;">
                                <label style="font-size: 12px; color: #556">Màu:</label>
                                <input type="text" id="textColor" />

                                <label style="font-size: 12px; color: #556; margin-left:5px">Viền:</label>
                                <input type="text" id="strokeColor" />

                                <input id="strokeWidth" type="number" min="0" max="10" value="0"
                                    style="width: 50px" title="Độ dày viền" class="input is-small" />
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 1rem">
                        <div class="card-content">
                            <p class="title is-6 mb-3">Chọn mẫu áo</p>

                            <div class="field mb-3">
                                <label class="label is-small has-text-grey">Loại sản phẩm:</label>
                                <div class="control">
                                    <div class="select is-fullwidth is-small">
                                        <select id="productSelect">
                                            @foreach ($products as $index => $product)
                                                <option value="{{ $product->id }}" {{ $index === 0 ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <label class="label is-small has-text-grey">Màu sắc:</label>

                            <div class="ds-slider-container">
                                <button class="ds-nav-btn" id="btnPrevShirt">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <div class="ds-slider-track" id="shirtTrack">
                                    @foreach ($products as $product)
                                        @foreach ($product->colors as $color)
                                            <img class="ds-shirt-item product-group-{{ $product->id }}"
                                                title="{{ $color->color_name }}" data-key="{{ $color->id }}"
                                                data-product-id="{{ $product->id }}"
                                                data-front="{{ asset($color->image_front) }}"
                                                data-back="{{ asset($color->image_back) }}"
                                                src="{{ asset($color->image_front) }}" style="display: none;" />
                                        @endforeach
                                    @endforeach
                                </div>

                                <button class="ds-nav-btn" id="btnNextShirt">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <p class="has-text-centered is-size-7 mt-2 has-text-grey" id="selectedColorName">
                                Đang chọn: ---
                            </p>

                            <a class="button is-small is-link is-light is-fullwidth requires-garment" id="toggleView"
                                style="margin-top: 8px">
                                Xem mặt sau
                            </a>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 1rem">
                        <div class="card-content">
                            <p class="title is-6">Thông tin đặt hàng</p>
                            <div class="field">
                                <label class="label is-small">Tên thiết kế</label>
                                <div class="control">
                                    <input class="input is-small" type="text" id="designNameInput"
                                        placeholder="VD: Áo lớp 12A" />
                                </div>
                            </div>
                            <div class="field is-horizontal">
                                <div class="field-body">
                                    <div class="field">
                                        <label class="label is-small">Size</label>
                                        <div class="control">
                                            <div class="select is-fullwidth is-small">
                                                <select id="sizeInput">
                                                    <option value="M">M</option>
                                                    <option value="L">L</option>
                                                    <option value="XL">XL</option>
                                                    <option value="XXL">XXL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="label is-small">Số lượng</label>
                                        <div class="control">
                                            <input class="input is-small" type="number" id="qtyInput" value="1"
                                                min="1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="button is-primary is-fullwidth requires-garment" id="btnAddToCart">
                                <span class="icon"><i class="fas fa-cart-plus"></i></span>
                                <span>Thêm vào giỏ & Lưu</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="column is-6">
                    <div class="card">
                        <div class="card-content"
                            style="padding: 0; display: flex; justify-content: center; background: #f5f5f5;">
                            <canvas id="c1" width="550" height="650"></canvas>
                        </div>
                    </div>
                </div>

                <div class="column is-3">
                    <div class="card" id="cart">
                        <div class="card-content">
                            <div class="cart-summary-title">
                                <p class="title is-6">Giỏ hàng tạm</p>
                                <span class="tag is-info" id="cartCountTag">0 SP</span>
                            </div>
                            <div id="cartItems"
                                style="margin-top: 0.75rem; font-size: 0.85rem; max-height: 150px; overflow-y: auto;">
                                Chưa có sản phẩm nào.
                            </div>
                            <a href="{{ route('cart.index') }}" class="button is-small is-link is-outlined is-fullwidth"
                                style="margin-top:10px">
                                Xem giỏ hàng chi tiết
                            </a>
                        </div>
                    </div>

                    <nav class="panel" style="margin-top: 1rem; background: white;">
                        <p class="panel-heading">Quản lý lớp (Layers)</p>
                        <div id="layers-container">
                            <a class="panel-block custom-layer is-active" id="baseLayer">Lớp nền (Áo)</a>
                        </div>
                        <div class="panel-block">
                            <button class="button is-link is-outlined is-fullwidth is-small" id="newLayerButton">
                                Thêm nhóm lớp
                            </button>
                        </div>
                    </nav>

                    <nav class="panel layer-objects" style="background: white;">
                        <p class="panel-heading" style="font-size: 0.9rem">Đối tượng đang chọn</p>
                    </nav>
                </div>
            </div>
        </div>

        <div class="modal imageLoadURLDialog">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Tải ảnh lên</p>
                    <button class="delete" aria-label="close" id="closeImageModalDialog"></button>
                </header>
                <section class="modal-card-body">
                    <div class="file has-name is-fullwidth">
                        <label class="file-label">
                            <input class="file-input" type="file" id="customImageURL" accept="image/*">
                            <span class="file-cta">
                                <span class="file-icon"><i class="fas fa-upload"></i></span>
                                <span class="file-label">Chọn file...</span>
                            </span>
                        </label>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" id="addImageFromURL">Tải lên</button>
                    <button class="button" id="cancelImageModalDialog">Hủy</button>
                </footer>
            </div>
        </div>

        <div class="modal galleryModalDialog">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Chọn hình ảnh sẵn có</p>
                    <button class="delete" aria-label="close" id="closeGalleryDialog"></button>
                </header>

                <section class="modal-card-body">
                    <div class="columns">

                        @foreach ($cliparts->split(3) as $chunk)
                            <div class="column">
                                @foreach ($chunk as $art)
                                    {{-- Cấu trúc item giữ nguyên như HTML gốc --}}
                                    <figure class="image is-square" style="margin-bottom: 5px;">
                                        <img src="{{ asset($art->image_url) }}" alt="{{ $art->name }}">
                                    </figure>
                                    <input name="clipart" value="{{ asset($art->image_url) }}" type="checkbox" />
                                    {{-- Thêm style margin để input cách hình tiếp theo một chút cho đẹp --}}
                                    <div style="margin-bottom: 15px;"></div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </section>

                <footer class="modal-card-foot">
                    <button class="button is-success" id="addClipart">Chọn</button>
                    <button class="button" id="cancelGalleryDialog">Đóng</button>
                </footer>
            </div>
        </div>

        <div class="modal layerModalDialog">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Tên lớp mới</p>
                    <button class="delete" aria-label="close" id="closeLayerModalDialog"></button>
                </header>
                <section class="modal-card-body">
                    <input class="input" type="text" id="customLayerTitle" placeholder="Nhập tên..." />
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" id="addLayer">Tạo</button>
                    <button class="button" id="cancelLayerModalDialog">Hủy</button>
                </footer>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        // ============================================================
        // 1. CẤU HÌNH & UI CƠ BẢN
        // ============================================================

        // Cấu hình CSRF Token cho mọi request Ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Xử lý nút cuộn Slider (Dùng ID mới)
        $("#btnPrevShirt").click(function() {
            document.getElementById("shirtTrack").scrollBy({
                left: -110,
                behavior: "smooth"
            });
        });

        $("#btnNextShirt").click(function() {
            document.getElementById("shirtTrack").scrollBy({
                left: 110,
                behavior: "smooth"
            });
        });

        // ============================================================
        // 2. LOGIC QUẢN LÝ SẢN PHẨM & MÀU SẮC
        // ============================================================

        // Hàm lọc màu dựa trên ID sản phẩm
        function filterColorsByProduct(productId) {
            // 1. Ẩn tất cả ảnh màu
            $(".ds-shirt-item").hide().removeClass('visible');

            // 2. Chỉ hiện ảnh màu thuộc Product ID đang chọn
            var $visibleColors = $(`.ds-shirt-item.product-group-${productId}`);
            $visibleColors.show().addClass('visible');

            // 3. Reset thanh cuộn về đầu
            document.getElementById("shirtTrack").scrollLeft = 0;

            // 4. Tự động click chọn màu đầu tiên
            if ($visibleColors.length > 0) {
                // Chỉ click nếu chưa chọn hoặc đang chọn sản phẩm khác
                $visibleColors.first().click();
            } else {
                console.warn("Sản phẩm này chưa cập nhật màu sắc!");
            }
        }

        // Sự kiện khi thay đổi Select Box (Loại áo)
        $("#productSelect").change(function() {
            var selectedPid = $(this).val();
            filterColorsByProduct(selectedPid);
        });

        // Sự kiện khi CLICK chọn 1 màu áo
        $("#shirtTrack").on("click", ".ds-shirt-item", function() {
            var $this = $(this);
            var gid = $this.data("key");

            // UI: Đổi viền active
            $(".ds-shirt-item").removeClass("active");
            $this.addClass("active");

            // Lưu trạng thái mặt cũ trước khi đổi áo khác
            if (currentGarment && currentGarment.id != gid) {
                saveCurrentSide();
            }

            // Cập nhật thông tin áo hiện tại
            currentGarment = {
                id: gid,
                front: $this.data("front"),
                back: $this.data("back"),
                name: $this.attr("title")
            };
            currentView = "front";
            garmentChosen = true;

            // UI updates
            $("a.requires-garment").removeClass("disabled").removeAttr("disabled");
            $("#toggleView").text("Xem mặt sau");
            $("#selectedColorName").html(
            `Đang chọn: <strong class="has-text-dark">${$this.attr("title")}</strong>`);

            // Load Canvas mặt trước
            loadGarmentSide("front");
        });

        // ============================================================
        // 3. KHỞI TẠO FABRIC.JS & QUẢN LÝ STATE
        // ============================================================
        var canvas = new fabric.Canvas("c1", {
            preserveObjectStacking: true // Giữ thứ tự lớp khi chọn
        });

        var currentGarment = null;
        var currentView = "front";
        var garmentChosen = false;
        var designStore = {}; // Nơi lưu trữ thiết kế { id: { front: [], back: [] } }

        // Biến cho Polygon (Vùng in)
        var clipPoly = null;
        var startDrawingPolygon = false;
        var polygonPoints = [];

        // --- KHỞI CHẠY LẦN ĐẦU ---
        $(document).ready(function() {
            // Kiểm tra URL param để load đúng sản phẩm từ trang chủ
            const urlParams = new URLSearchParams(window.location.search);
            const urlPid = urlParams.get('product_id');
            const urlColorId = urlParams.get('color_id');

            if (urlPid) {
                $("#productSelect").val(urlPid);
                filterColorsByProduct(urlPid);

                if (urlColorId) {
                    setTimeout(() => {
                        $(`.ds-shirt-item[data-key="${urlColorId}"]`).click();
                    }, 100);
                }
            } else {
                // Mặc định load sản phẩm đầu tiên
                var firstPid = $("#productSelect").val();
                if (firstPid) filterColorsByProduct(firstPid);
            }

            // Khóa nút nếu chưa có áo
            if (!garmentChosen) $("a.requires-garment").addClass("disabled").attr("disabled", true);
        });

        // --- HÀM LOAD ÁO LÊN CANVAS ---
        function loadGarmentSide(side, callback = null) {
            canvas.discardActiveObject();
            canvas.clear();

            // Reset công cụ Polygon
            polygonPoints = [];
            startDrawingPolygon = false;
            $("#drawPolygon").removeClass("is-danger");

            var imgUrl = (side === 'front') ? currentGarment.front : currentGarment.back;

            // Load ảnh áo nền
            fabric.Image.fromURL(imgUrl, function(img) {
                img.set({
                    id: "baseLayer",
                    selectable: false,
                    evented: false,
                    hoverCursor: "default"
                });

                // Scale ảnh vừa canvas
                var scale = Math.min(canvas.width / img.width, canvas.height / img.height);
                img.scale(scale);
                img.center();
                canvas.add(img);
                canvas.sendToBack(img);

                // Load các object thiết kế cũ từ bộ nhớ
                if (designStore[currentGarment.id] && designStore[currentGarment.id][side]) {
                    var json = designStore[currentGarment.id][side];
                    fabric.util.enlivenObjects(json, function(objects) {
                        objects.forEach(function(o) {
                            o.set({
                                selectable: true,
                                hasControls: true
                            });
                            canvas.add(o);
                        });
                        canvas.renderAll();
                        if (callback) callback();
                    });
                } else {
                    canvas.renderAll();
                    if (callback) callback();
                }
            });

            $("#toggleView").text(side === 'front' ? "Xem mặt sau" : "Xem mặt trước");
        }

        // --- HÀM LƯU TRẠNG THÁI HIỆN TẠI VÀO RAM ---
        function saveCurrentSide() {
            if (!currentGarment) return;
            if (!designStore[currentGarment.id]) designStore[currentGarment.id] = {
                front: [],
                back: []
            };

            // Lấy objects trừ áo nền và marker vẽ
            var objects = canvas.getObjects().filter(o =>
                o.id !== 'baseLayer' && o.id !== 'circle_marker' && o.id !== 'line_marker'
            );
            designStore[currentGarment.id][currentView] = objects.map(o => o.toObject(['id', 'name']));
        }

        // --- NÚT ĐỔI MẶT (TOGGLE VIEW) ---
        $("#toggleView").click(function() {
            if (!garmentChosen) return;
            saveCurrentSide();
            currentView = (currentView === 'front') ? 'back' : 'front';
            loadGarmentSide(currentView);
        });

        // ============================================================
        // 4. CÔNG CỤ THIẾT KẾ (TEXT, UPLOAD, CLIPART)
        // ============================================================

        // Thêm Text
        $("#addText").click(function() {
            var text = new fabric.IText("Nội dung...", {
                left: canvas.width / 2 - 50,
                top: canvas.height / 3,
                fill: $("#textColor").val() || "#000000",
                stroke: $("#strokeColor").val() || "#000000",
                strokeWidth: parseInt($("#strokeWidth").val()) || 0,
                fontFamily: "Arial"
            });
            canvas.add(text);
            canvas.setActiveObject(text);
        });

        // Color Picker (Spectrum)
        if ($.fn.spectrum) {
            $("#textColor").spectrum({
                color: "#000",
                showInput: true,
                showPalette: true,
                change: function(c) {
                    var obj = canvas.getActiveObject();
                    if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                        obj.set('fill', c.toHexString());
                        canvas.renderAll();
                    }
                }
            });
            $("#strokeColor").spectrum({
                color: "#000",
                showInput: true,
                showPalette: true,
                change: function(c) {
                    var obj = canvas.getActiveObject();
                    if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                        obj.set('stroke', c.toHexString());
                        canvas.renderAll();
                    }
                }
            });
        }

        // Input độ dày viền
        $("#strokeWidth").on("input", function() {
            var val = parseInt($(this).val());
            var obj = canvas.getActiveObject();
            if (obj) {
                obj.set('strokeWidth', val);
                canvas.renderAll();
            }
        });

        // Cập nhật Toolbar khi chọn Object
        canvas.on("selection:created", updateTools);
        canvas.on("selection:updated", updateTools);

        function updateTools(e) {
            var obj = e.target;
            $("#objectInfo").text("Đang chọn: " + obj.type);
            if ($.fn.spectrum && (obj.type === 'i-text' || obj.type === 'text')) {
                $("#textColor").spectrum("set", obj.fill);
                $("#strokeColor").spectrum("set", obj.stroke);
                $("#strokeWidth").val(obj.strokeWidth || 0);
            }
        }
        canvas.on("selection:cleared", () => $("#objectInfo").text("Chưa chọn đối tượng nào"));

        // --- UPLOAD ẢNH ---
        $("#addImageModalDialog").click(() => $(".imageLoadURLDialog").addClass("is-active"));
        $("#closeImageModalDialog, #cancelImageModalDialog").click(() => $(".imageLoadURLDialog").removeClass("is-active"));

        $("#addImageFromURL").click(function() {
            var fileInput = document.getElementById('customImageURL');
            if (fileInput.files.length === 0) return alert("Chưa chọn file!");

            var fd = new FormData();
            fd.append('image', fileInput.files[0]);
            var btn = $(this).addClass('is-loading');

            $.ajax({
                url: '/api/upload-image', // Route Laravel upload
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(res) {
                    fabric.Image.fromURL(res.url, function(img) {
                        img.scaleToWidth(200);
                        img.set({
                            left: 150,
                            top: 150
                        });
                        canvas.add(img);
                        canvas.setActiveObject(img);
                    });
                    $(".imageLoadURLDialog").removeClass("is-active");
                    fileInput.value = "";
                },
                error: function(err) {
                    alert("Lỗi upload: " + err.statusText);
                },
                complete: function() {
                    btn.removeClass('is-loading');
                }
            });
        });

        // --- CLIPART ---
        $("#galleryDialog").click(() => $(".galleryModalDialog").addClass("is-active"));
        $("#closeGalleryDialog, #cancelGalleryDialog").click(() => $(".galleryModalDialog").removeClass("is-active"));

        $("#addClipart").click(function() {
            $("input[name='clipart']:checked").each(function() {
                var src = $(this).val();
                fabric.Image.fromURL(src, function(img) {
                    img.scaleToWidth(150);
                    img.set({
                        left: 100,
                        top: 100
                    });
                    canvas.add(img);
                });
            });
            $(".galleryModalDialog").removeClass("is-active");
            $("input[name='clipart']").prop("checked", false);
        });

        // --- XÓA / RESET ---
        $("#removeSelectedObject").click(() => {
            var a = canvas.getActiveObject();
            if (a) canvas.remove(a);
        });
        $("#clearViewport").click(() => {
            if (confirm("Xóa toàn bộ thiết kế trên mặt này?")) {
                canvas.getObjects().forEach(o => {
                    if (o.id !== 'baseLayer') canvas.remove(o);
                });
                designStore[currentGarment.id][currentView] = [];
            }
        });

        // ============================================================
        // 5. VẼ VÙNG IN (POLYGON CLIPPING)
        // ============================================================
        $("#drawPolygon").click(function() {
            startDrawingPolygon = !startDrawingPolygon;
            $(this).toggleClass("is-danger", startDrawingPolygon);

            if (startDrawingPolygon) {
                // Xóa các điểm cũ
                canvas.getObjects().forEach(o => {
                    if (o.id === 'circle_marker' || o.id === 'line_marker' || o.id === 'fixedLayer') canvas
                        .remove(o);
                });
                polygonPoints = [];
                clipPoly = null;
            } else {
                // Kết thúc vẽ -> Tạo Polygon
                if (polygonPoints.length > 2) {
                    var coords = polygonPoints.map(p => ({
                        x: p.left,
                        y: p.top
                    }));
                    clipPoly = new fabric.Polygon(coords, {
                        fill: 'rgba(0,0,0,0.1)',
                        stroke: '#666',
                        strokeWidth: 1,
                        strokeDashArray: [5, 5],
                        selectable: false,
                        evented: false,
                        id: 'fixedLayer'
                    });
                    canvas.add(clipPoly);
                    // Dọn dẹp marker
                    canvas.getObjects().forEach(o => {
                        if (o.id === 'circle_marker' || o.id === 'line_marker') canvas.remove(o);
                    });
                }
            }
        });

        canvas.on("mouse:down", function(opt) {
            if (!startDrawingPolygon) return;
            var ptr = canvas.getPointer(opt.e);
            var circle = new fabric.Circle({
                left: ptr.x,
                top: ptr.y,
                radius: 5,
                fill: 'red',
                hasControls: false,
                hasBorders: false,
                originX: 'center',
                originY: 'center',
                id: 'circle_marker'
            });
            canvas.add(circle);
            polygonPoints.push(circle);

            if (polygonPoints.length > 1) {
                var p1 = polygonPoints[polygonPoints.length - 2];
                var p2 = polygonPoints[polygonPoints.length - 1];
                var line = new fabric.Line([p1.left, p1.top, p2.left, p2.top], {
                    stroke: 'red',
                    strokeWidth: 2,
                    selectable: false,
                    evented: false,
                    id: 'line_marker'
                });
                canvas.add(line);
                canvas.sendToBack(line);
            }
        });

        // ============================================================
        // 6. LƯU & THÊM VÀO GIỎ HÀNG (AJAX CHAINING)
        // ============================================================
        $("#btnAddToCart").click(function() {
            if (!garmentChosen) return alert("Vui lòng chọn áo trước!");

            var btn = $(this).addClass("is-loading");
            saveCurrentSide(); // Lưu mặt hiện tại

            // --- BƯỚC 1: Load mặt trước & Chụp ảnh ---
            loadGarmentSide('front', function() {
                var fImg = canvas.toDataURL({
                    format: 'png',
                    multiplier: 0.5
                });

                // --- BƯỚC 2: Load mặt sau & Chụp ảnh ---
                loadGarmentSide('back', function() {
                    var bImg = canvas.toDataURL({
                        format: 'png',
                        multiplier: 0.5
                    });

                    // --- BƯỚC 3: Gửi lên Server ---
                    sendToBackend(fImg, bImg);
                });
            });

            function sendToBackend(fBase64, bBase64) {
                var payload = {
                    variant_id: currentGarment.id,
                    name: $("#designNameInput").val() || "Thiết kế của tôi",
                    size: $("#sizeInput").val(),
                    quantity: $("#qtyInput").val(),

                    // Dữ liệu JSON để sửa lại sau này
                    front_json: JSON.stringify(designStore[currentGarment.id]['front'] || []),
                    back_json: JSON.stringify(designStore[currentGarment.id]['back'] || []),

                    // Ảnh chụp màn hình
                    front_img_base64: fBase64,
                    back_img_base64: bBase64
                };

                // Gọi API lưu thiết kế
                $.post('/api/save-design', payload)
                    .done(function(res) {
                        // Lưu xong -> Gọi tiếp API thêm vào Session Giỏ hàng
                        $.post("{{ route('cart.add') }}", {
                                design_id: res.id,
                                size: payload.size,
                                quantity: payload.quantity
                            })
                            .done(function(cartRes) {
                                // Cập nhật số lượng trên Menu
                                if (cartRes.cart_count !== undefined) {
                                    $("#navCartCount, #cartCountTag").text(cartRes.cart_count + " SP");
                                }

                                // Hiệu ứng & Thông báo
                                $("#navCartCount").fadeOut(100).fadeIn(100);
                                alert("Đã thêm vào giỏ hàng thành công!");

                                // Chuyển hướng (nếu cần)
                                // window.location.href = "{{ route('cart.index') }}";
                            });
                    })
                    .fail(function(err) {
                        alert("Lỗi server: " + (err.responseJSON ? err.responseJSON.message : err.statusText));
                    })
                    .always(function() {
                        btn.removeClass("is-loading");
                        // Quay về mặt trước cho user tiếp tục
                        loadGarmentSide('front');
                    });
            }
        });
    </script>
@endpush
