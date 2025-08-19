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
                            <button type="button" onclick="decreaseQuantity()">−</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock ?? 1 }}" readonly>
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                    <button class="add-cart" onclick="addToCart('{{ $product->id }}', '{{ $product->name }}', '{{ $product->price }}', '{{ $product->image_url }}')">
                        Thêm vào giỏ hàng
                    </button>
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
@endsection
