@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')
    <h2>Thanh toán</h2>

    @if(count($cart) > 0)
        <h3>Giỏ hàng</h3>
        <ul>
            @foreach($cart as $item)
                <li>{{ $item['name'] }} - {{ $item['quantity'] }} x {{ number_format($item['price']) }} VNĐ</li>
            @endforeach
        </ul>
        <p><strong>Tổng tiền:</strong> {{ number_format($total) }} VNĐ</p>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <label>Địa chỉ giao hàng:</label><br>
            <select name="selected_address" onchange="document.getElementById('newAddress').style.display = (this.value === 'new') ? 'block' : 'none'">
                <option value="">-- Chọn địa chỉ --</option>
                @foreach($addresses as $addr)
                    <option value="{{ $addr }}">{{ $addr }}</option>
                @endforeach
                <option value="new">Nhập địa chỉ mới</option>
            </select>
            <div id="newAddress" style="display:none;">
                <input type="text" name="new_address" placeholder="Nhập địa chỉ mới">
            </div>

            <br><br>

            <label>Số điện thoại:</label><br>
            <select name="selected_phone" onchange="document.getElementById('newPhone').style.display = (this.value === 'new') ? 'block' : 'none'">
                <option value="">-- Chọn SĐT --</option>
                @foreach($phones as $ph)
                    <option value="{{ $ph }}">{{ $ph }}</option>
                @endforeach
                <option value="new">Nhập SĐT mới</option>
            </select>
            <div id="newPhone" style="display:none;">
                <input type="text" name="new_phone" placeholder="Nhập SĐT mới">
            </div>


            <button type="submit">Xác nhận đặt hàng</button>
        </form>
    @else
        <p>Giỏ hàng trống.</p>
    @endif
@endsection
