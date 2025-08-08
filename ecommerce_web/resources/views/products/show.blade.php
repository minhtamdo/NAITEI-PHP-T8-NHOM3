@extends('layouts.user')

@section('title', $product->name)

@section('content')
    <div class="product-detail">
        <h2>{{ $product->name }}</h2>
        <img src="{{ asset('images/' . $product->image) }}" width="250" height="300">
        <p>Số lượng còn: {{ $product->stock }}</p>
        <p><strong>Giá:</strong> {{ number_format($product->price) }} VNĐ</p>
        <p><strong>Mô tả:</strong> {{ $product->description }}</p>

        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="name" value="{{ $product->name }}">
            <input type="hidden" name="price" value="{{ $product->price }}">
            <input type="hidden" name="image" value="{{ $product->image }}">
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
            <button type="submit">Thêm vào giỏ hàng</button>
        </form>

        <hr>
        <h3>Gửi đánh giá</h3>
        @if (session('success'))
            <p style="color: green">{{ session('success') }}</p>
        @endif
        <form method="POST" action="{{ route('products.review', $product->id) }}">
            @csrf
            <input type="text" name="author" placeholder="Tên của bạn" required><br><br>
            <textarea name="content" placeholder="Nội dung đánh giá" required></textarea><br><br>
            <button type="submit">Gửi đánh giá</button>
        </form>

        <h3>Đánh giá sản phẩm</h3>
        @if (!empty($reviews))
            <ul>
                @foreach ($reviews as $review)
                    <li><strong>{{ $review['author'] }}:</strong> {{ $review['content'] }}</li>
                @endforeach
            </ul>
        @else
            <p>Chưa có đánh giá nào.</p>
        @endif

        <p><a href="{{ url('/products') }}">← Quay lại danh sách</a></p>
    </div>
@endsection
