@extends('layouts.user')

@section('title', 'Giỏ hàng')

@section('content')
    <h2>Giỏ hàng của bạn</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if(count($cart) > 0)
        <table border="1" cellpadding="10">
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
                <th>Hành động</th>
            </tr>

            @foreach($cart as $index => $item)
                <tr>
                    <td><img src="{{ asset('images/' . $item['image']) }}" width="80"></td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['price']) }} VNĐ</td>
                    <td>
                        <form action="{{ route('cart.update', $index) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1">
                            <button type="submit">Cập nhật</button>
                        </form>
                    </td>
                    <td>{{ number_format($item['price'] * $item['quantity']) }} VNĐ</td>
                    <td>
                        <form action="{{ route('cart.remove', $index) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Giỏ hàng trống.</p>
    @endif
    <h3>Tổng tiền: {{ number_format($total) }}đ</h3>
    @if(count($cart) > 0)
        <form action="{{ route('checkout') }}" method="GET">
            <button type="submit">Thanh toán</button>
        </form>
    @endif

@endsection
