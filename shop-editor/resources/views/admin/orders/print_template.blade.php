<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>In Phiếu Giao Hàng</title>
    <style>
        body {
            font-family: 'Deja Vu Sans', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #eee;
        }

        /* Một tờ phiếu */
        .invoice-box {
            background: #fff;
            max-width: 800px;
            margin: 0 auto 20px auto;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        /* Tiêu đề */
        .header-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .shop-info {
            font-size: 12px;
            color: #555;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        /* Thông tin khách hàng (Quan trọng để Ship) */
        .shipping-info {
            border: 2px dashed #000;
            padding: 15px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }

        .shipping-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
        }

        .receiver-name {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }

        .receiver-phone {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .receiver-address {
            font-size: 16px;
            line-height: 1.4;
        }

        /* Bảng sản phẩm */
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        table th {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 12px;
        }

        /* Tổng tiền */
        .total-section {
            text-align: right;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        /* CSS CHO MÁY IN (QUAN TRỌNG) */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                border: 0;
                margin: 0;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }

            /* Lệnh ngắt trang: Mỗi đơn hàng là 1 trang in */
            .page-break {
                page-break-after: always;
                display: block;
                height: 0;
            }

            /* Ẩn nút in khi in */
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #333; color: #fff; border: none;">
            🖨️ NHẤN VÀO ĐÂY ĐỂ IN NGAY
        </button>
        <p>Tìm thấy {{ count($orders) }} đơn hàng trạng thái "Đang đóng gói" (Packing)</p>
    </div>

    @forelse($orders as $order)
        <div class="invoice-box">
            <div class="shop-info">
                <div class="header-title">PHIẾU GIAO HÀNG (COD)</div>
                <span>Mã đơn: <strong>#{{ $order->id }}</strong></span> |
                <span>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                <div style="float: right;">
                    <strong>MyTee Studio</strong><br>
                    Hotline: 0909.xxx.xxx
                </div>
            </div>

            <div class="shipping-info">
                <div class="shipping-label">Người nhận (To):</div>
                <div class="receiver-name">{{ $order->customer_name }}</div>
                <div class="receiver-phone">{{ $order->phone }}</div>
                <div class="receiver-address">{{ $order->shipping_address }}</div>
                @if ($order->note)
                    <div style="margin-top: 10px; font-style: italic; font-size: 12px;">
                        <strong>Ghi chú:</strong> {{ $order->note }}
                    </div>
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center">Size</th>
                        <th style="text-align: center">SL</th>
                        <th style="text-align: right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->savedDesign->design_name ?? 'Áo thun thiết kế' }}
                                <br>
                                <span style="font-size: 11px; color: #777;">
                                    {{-- SỬA LẠI ĐOẠN NÀY ĐỂ TRÁNH LỖI --}}
                                    ({{ $item->savedDesign?->variant?->name ?? 'Sản phẩm đã xóa' }}
                                    -
                                    {{ $item->savedDesign?->variant?->colors?->first()?->color_name ?? 'Màu mặc định' }})
                                </span>
                            </td>
                            <td style="text-align: center">{{ $item->size }}</td>
                            <td style="text-align: center"><strong>{{ $item->quantity }}</strong></td>
                            <td style="text-align: right">
                                {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                Tổng thu người nhận: {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
            </div>

            <div style="margin-top: 30px; text-align: center; font-size: 12px; font-style: italic;">
                Cảm ơn bạn đã đặt hàng tại MyTee Studio! <br>
                Vui lòng quay video khi mở hàng để được hỗ trợ đổi trả.
            </div>
        </div>

        <div class="page-break"></div>

    @empty
        <div style="text-align: center; padding: 50px;">
            <h2>Không có đơn hàng nào đang ở trạng thái "Đóng gói" trong khoảng thời gian này.</h2>
            <button onclick="window.close()">Đóng cửa sổ</button>
        </div>
    @endforelse

</body>

</html>
