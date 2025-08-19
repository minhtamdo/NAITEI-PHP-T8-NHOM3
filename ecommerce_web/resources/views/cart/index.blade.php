@extends('layouts.user')

@section('title', 'Giỏ hàng')

@vite(['resources/css/cart/index.css', 'resources/js/cart/index.js'])

@section('content')
<body>
    <div class="container">
    <div class="header">
        <h1>Giỏ Hàng</h1>
        <p>Những cuốn sách bạn đã chọn</p>
    </div>

    {{-- Nếu có sản phẩm trong giỏ --}}
    @if(!empty($cart) && count($cart) > 0)
        <div class="cart-content">
            <div class="cart-items">
                @foreach($cart as $item)
                    <div class="cart-item">
                        {{-- Ảnh bìa --}}
                        <div class="book-cover">
                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/80x100' }}"
                                 alt="{{ $item['name'] }}"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>

                        {{-- Thông tin sách --}}
                        <div class="book-info">
                            <h3>{{ $item['name'] }}</h3>
                            <p>Tác giả: {{ $item['author'] ?? 'Không rõ' }}</p>
                            <p>Thể loại: {{ $item['category'] ?? 'Chưa phân loại' }}</p>
                        </div>

                        {{-- Điều khiển số lượng --}}
                        <div class="quantity-controls">
                            <form action="#" method="POST">
                            @csrf
                                <button type="button" class="quantity-btn minus">-</button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="quantity-input">
                                <button type="button" class="quantity-btn plus">+</button>
                        </form>
                        </div>

                        {{-- Giá --}}
                        <div class="price">
                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                        </div>

                        {{-- Xóa --}}
                        <form action="#" method="POST">
                            @csrf
                            <button class="remove-btn">×</button>
                        </form>
                    </div>
            @endforeach
            </div>

            {{-- Tóm tắt đơn hàng --}}
            <div class="cart-summary">
                <h2>Tóm tắt đơn hàng</h2>
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($subtotal ?? 0, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format($shipping ?? 30000, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span>{{ number_format(($subtotal ?? 0) + ($shipping ?? 30000), 0, ',', '.') }}đ</span>
                </div>

                <form action="#" method="GET">
                    <button type="submit" class="checkout-btn">Thanh toán</button>
                </form>

                <div class="continue-shopping">
                    <a href="#">← Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @else
        {{-- Giỏ hàng trống --}}
        <div class="empty-cart">
            <h2>Giỏ hàng của bạn đang trống</h2>
            <p>Hãy khám phá những cuốn sách tuyệt vời của chúng tôi</p>
            <a href="#">Bắt đầu mua sắm →</a>
        </div>
    @endif
</div>
</body>
@endsection
