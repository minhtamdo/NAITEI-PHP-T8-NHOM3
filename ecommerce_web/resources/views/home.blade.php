@extends('layouts.user')

@section('title', 'Trang chủ')

@section('content')
    <h1>Chào mừng đến với cửa hàng của chúng tôi!</h1>
    <p>Khám phá các sản phẩm mới nhất và ưu đãi hấp dẫn.</p>

    <hr>

    <h2>Sản phẩm nổi bật</h2>
    <ul>
        @foreach ($products as $product)
            <li>
                <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
            </li>
        @endforeach
    </ul>

    <hr>

    <h2>Khuyến mãi trong tuần</h2>
    <p>Mua 2 tặng 1 cho tất cả các sản phẩm thời trang.</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Xem tất cả sản phẩm</a>

    <hr>

    <h2>Liên hệ</h2>
    <p>📍 Địa chỉ: 123 Đường ABC, Quận X, TP. Hà Nội</p>
    <p>📞 Hotline: 0123 456 789</p>
    <p>✉ Email: support@cuahang.com</p>
@endsection
