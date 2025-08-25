@extends('layouts.user')

@section('title', $product->name)

@section('content')

@vite(['resources/css/products/show.css', 'resources/js/products/show.js'])

<body>
    <div class="container">
        <a href="javascript:history.back()" class="back">← Quay lại</a>

        <div class="product">
            <div class="image">
                <div style="position: relative;">
                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/600x400?text=No+Image' }}" 
                        alt="{{ $product->name }}">
                    <div class="stock">{{ $product->stock ?? 0 }} cuốn</div>
                </div>
            </div>

            <div class="info">
                <h1>{{ $product->name }}</h1>
                <div class="price">{{ number_format($product->price,0,',','.') }}đ</div>
                <p class="description">
                    {{ $product->description ?? 'Chưa có mô tả cho sản phẩm này.' }}
                </p>

                <div class="purchase">
                    <div class="quantity-row">
                        <span>Số lượng</span>
                        <div class="quantity">
                            <button type="button" class="quantity-btn minus" data-product-id="{{ $product->id }}">−</button>
                            <input type="number" id="quantity-{{ $product->id }}" value="1" min="1" max="{{ $product->stock ?? 1 }}" readonly>
                            <button type="button" class="quantity-btn plus" data-product-id="{{ $product->id }}">+</button>
                        </div>
                    </div>
                    @auth
                        <form id="add-to-cart-form-{{ $product->id }}" action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="quantity-input-{{ $product->id }}" value="1">
                            <button type="submit" class="add-cart">Thêm vào giỏ hàng</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="add-cart">Thêm vào giỏ hàng</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="reviews">
            <h2 class="section-title">Đánh giá</h2>

            <div class="form">
                <div id="success" class="success">
                    Cảm ơn bạn đã đánh giá sản phẩm.
                    </div>

                <form onsubmit="submitReview(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ tên</label>
                            <input type="text" id="name" required>
                        </div>
                    <div class="form-group">
                            <label>Email</label>
                            <input type="email">
                        </div>
                        <div class="form-group full">
                        <label>Nhận xét</label>
                            <textarea id="content" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="submit">Gửi đánh giá</button>
                </form>
            </div>

            <div class="review-list" id="reviews">
                <!-- Reviews tĩnh giữ nguyên -->
                    <div class="review">
                        <div class="review-header">
                        <span class="review-name">Minh Hoàng</span>
                        <span class="review-date">2 ngày trước</span>
                        </div>
                        <div class="review-content">
                        Sách viết rất dễ hiểu, ví dụ thực tế và cập nhật. 
                        Phần về Laravel đặc biệt hữu ích cho công việc hiện tại của tôi.
                    </div>
                        </div>

                <div class="review">
                    <div class="review-header">
                        <span class="review-name">Thu Hằng</span>
                        <span class="review-date">1 tuần trước</span>
                    </div>
                    <div class="review-content">
                        Chất lượng tốt, giá cả hợp lý. Tôi đã giới thiệu cho nhiều đồng nghiệp.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif
@endsection
