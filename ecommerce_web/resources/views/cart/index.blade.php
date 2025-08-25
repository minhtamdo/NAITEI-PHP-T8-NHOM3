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
    @if(!empty($cartItems) && count($cartItems) > 0)
        <div class="cart-content">
            <div class="cart-items">
                @foreach ($cartItems as $item)
                <div class="cart-item" data-id="{{ $item->id }}">
                    {{-- Ảnh --}}
                    <div class="book-cover">
                            <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/80x100' }}" alt="{{ $item->product->name ?? '' }}" style="width:100%; height:100%; object-fit:cover;">
                    </div>

                    {{-- Thông tin --}}
                    <div class="book-info">
                        <h3>{{ $item->product->name }}</h3>
                        <p>Tác giả: {{ $item->product->author ?? 'Không rõ' }}</p>
                        <p>Thể loại: {{ $item->product->category->name ?? 'Chưa phân loại' }}</p>
                    </div>

                    {{-- Số lượng --}}
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn minus">-</button>
                        <input type="number" class="quantity-input" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock ?? 100 }}" data-price="{{ $item->product->price }}">
                        <button type="button" class="quantity-btn plus">+</button>
                    </div>

                    {{-- Giá --}}
                    <div class="price">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ</div>

                    {{-- Xoá --}}
                    <form class="remove-form" method="POST">
                        @csrf
                        <button type="button" class="remove-btn">×</button>
                    </form>
                    
                </div>
                @endforeach
            </div>

            {{-- Tóm tắt đơn hàng --}}
            <div class="cart-summary">
                <h2>Tóm tắt đơn hàng</h2>
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span id="subtotal">{{ number_format($subtotal ?? 0, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span id="shipping">{{ number_format($shipping ?? 30000, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span id="total">{{ number_format(($subtotal ?? 0) + ($shipping ?? 30000), 0, ',', '.') }}đ</span>
                </div>

                <form action="{{ route('checkout') }}" method="GET">
                    <button type="submit" class="checkout-btn">Thanh toán</button>
                </form>

                <div class="continue-shopping">
                    <a href="{{ route('home') }}">← Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @else
        {{-- Giỏ hàng trống --}}
        <div class="empty-cart">
            <h2>Giỏ hàng của bạn đang trống</h2>
            <p>Hãy khám phá những cuốn sách tuyệt vời của chúng tôi</p>
            <a href="{{ route('home') }}">Bắt đầu mua sắm →</a>
        </div>
    @endif
</div>
</body>
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif
@endsection
