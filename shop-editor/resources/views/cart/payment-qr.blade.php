@extends('layouts.app')

@section('title', 'Thanh toán QR - MyTee Studio')

@section('content')
<section class="section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-6">
                <div class="box" style="border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                    
                    {{-- Header --}}
                    <div class="has-text-centered mb-5">
                        <span class="icon is-large has-text-success" style="font-size: 3rem;">
                            <i class="fas fa-qrcode"></i>
                        </span>
                        <h1 class="title is-4 mt-3">Quét mã QR để thanh toán</h1>
                        <p class="subtitle is-6 has-text-grey">Đơn hàng #{{ $order->id }}</p>
                    </div>

                    {{-- QR Code --}}
                    <div class="has-text-centered mb-5">
                        <div style="background: white; padding: 20px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <img src="https://img.vietqr.io/image/{{ $bankInfo['bank_id'] }}-{{ $bankInfo['account_no'] }}-compact2.png?amount={{ $bankInfo['amount'] }}&addInfo={{ urlencode($bankInfo['description']) }}&accountName={{ urlencode($bankInfo['account_name']) }}" 
                                 alt="QR Code" 
                                 style="width: 300px; height: 300px; border-radius: 8px;">
                        </div>
                        <p class="help mt-3">Sử dụng app ngân hàng để quét mã QR</p>
                    </div>

                    {{-- Thông tin chuyển khoản --}}
                    <div class="box has-background-light">
                        <h3 class="title is-6 mb-3">
                            <span class="icon"><i class="fas fa-university"></i></span>
                            Thông tin chuyển khoản
                        </h3>
                        
                        <div class="field">
                            <label class="label is-small">Ngân hàng</label>
                            <div class="control">
                                <input class="input is-static" type="text" value="KIENLONGBANK" readonly>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label is-small">Số tài khoản</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input is-static" type="text" id="accountNo" value="{{ $bankInfo['account_no'] }}" readonly>
                                </div>
                                <div class="control">
                                    <button class="button is-info" onclick="copyText('accountNo')">
                                        <span class="icon"><i class="fas fa-copy"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label is-small">Chủ tài khoản</label>
                            <div class="control">
                                <input class="input is-static" type="text" value="{{ $bankInfo['account_name'] }}" readonly>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label is-small">Số tiền</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input is-static has-text-danger has-text-weight-bold" type="text" id="amount" value="{{ number_format($bankInfo['amount'], 0, ',', '.') }}₫" readonly>
                                </div>
                                <div class="control">
                                    <button class="button is-info" onclick="copyText('amount', '{{ $bankInfo['amount'] }}')">
                                        <span class="icon"><i class="fas fa-copy"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label is-small">Nội dung chuyển khoản</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input is-static has-text-primary has-text-weight-bold" type="text" id="description" value="{{ $bankInfo['description'] }}" readonly>
                                </div>
                                <div class="control">
                                    <button class="button is-info" onclick="copyText('description')">
                                        <span class="icon"><i class="fas fa-copy"></i></span>
                                    </button>
                                </div>
                            </div>
                            <p class="help has-text-danger">
                                <strong>Quan trọng:</strong> Vui lòng ghi đúng nội dung để đơn hàng được xử lý nhanh!
                            </p>
                        </div>
                    </div>

                    {{-- Hướng dẫn --}}
                    <div class="notification is-info is-light">
                        <p class="title is-6">📱 Hướng dẫn thanh toán:</p>
                        <ol style="margin-left: 20px; line-height: 1.8;">
                            <li>Mở app ngân hàng của bạn</li>
                            <li>Chọn chức năng <strong>Quét QR</strong></li>
                            <li>Quét mã QR phía trên</li>
                            <li>Kiểm tra thông tin và xác nhận chuyển khoản</li>
                            <li>Sau khi chuyển khoản thành công, bấm nút bên dưới</li>
                        </ol>
                    </div>

                    {{-- Nút hành động --}}
                    <div class="buttons mt-5">
                        <button class="button is-success is-fullwidth is-large" onclick="confirmPayment()">
                            <span class="icon"><i class="fas fa-check-circle"></i></span>
                            <span>Tôi đã chuyển khoản</span>
                        </button>
                        <a href="{{ route('orders.index') }}" class="button is-light is-fullwidth">
                            <span class="icon"><i class="fas fa-arrow-left"></i></span>
                            <span>Quay lại đơn hàng</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal xác nhận --}}
<div class="modal" id="confirmModal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head has-background-success">
            <p class="modal-card-title has-text-white">
                <span class="icon"><i class="fas fa-check-circle"></i></span>
                Xác nhận thanh toán
            </p>
            <button class="delete" aria-label="close" onclick="closeModal()"></button>
        </header>
        <section class="modal-card-body">
            <div class="content has-text-centered">
                <span class="icon is-large has-text-success" style="font-size: 4rem;">
                    <i class="fas fa-check-circle"></i>
                </span>
                <h3 class="title is-4 mt-4">Bạn đã chuyển khoản thành công?</h3>
                <p class="subtitle is-6 has-text-grey">
                    Vui lòng xác nhận rằng bạn đã hoàn tất việc chuyển khoản<br>
                    <strong class="has-text-danger">{{ number_format($bankInfo['amount'], 0, ',', '.') }}₫</strong><br>
                    với nội dung: <strong class="has-text-primary">{{ $bankInfo['description'] }}</strong>
                </p>
                <div class="notification is-warning is-light">
                    <p class="is-size-7">
                        <strong>Lưu ý:</strong> Đơn hàng sẽ được xử lý sau khi chúng tôi xác nhận thanh toán từ ngân hàng (thường trong vòng 5-10 phút).
                    </p>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot" style="justify-content: space-between;">
            <button class="button" onclick="closeModal()">Chưa, để tôi kiểm tra lại</button>
            <form action="{{ route('payment.confirm', $order->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="button is-success">
                    <span class="icon"><i class="fas fa-check"></i></span>
                    <span>Xác nhận đã chuyển khoản</span>
                </button>
            </form>
        </footer>
    </div>
</div>

<script>
    // Copy text to clipboard
    function copyText(elementId, rawValue = null) {
        const input = document.getElementById(elementId);
        const textToCopy = rawValue || input.value.replace(/[₫,\.]/g, '');
        
        navigator.clipboard.writeText(textToCopy).then(() => {
            // Hiệu ứng thông báo
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="icon"><i class="fas fa-check"></i></span>';
            btn.classList.add('is-success');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('is-success');
            }, 1500);
        });
    }

    // Mở modal xác nhận
    function confirmPayment() {
        document.getElementById('confirmModal').classList.add('is-active');
    }

    // Đóng modal
    function closeModal() {
        document.getElementById('confirmModal').classList.remove('is-active');
    }

    // Đóng modal khi click background
    document.querySelector('.modal-background')?.addEventListener('click', closeModal);
</script>

<style>
    .is-static {
        background-color: white !important;
        border-color: #dbdbdb !important;
        cursor: text !important;
    }
</style>
@endsection
