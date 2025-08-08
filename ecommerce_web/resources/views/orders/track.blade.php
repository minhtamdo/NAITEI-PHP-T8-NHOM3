@extends('layouts.user')

@section('title', 'Theo dõi đơn hàng')

@section('content')
    <h2>Đơn hàng của bạn</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if(count($orders) > 0)
        <table border="1" cellpadding="10">
            <tr>
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Sản phẩm</th>
                <th>Tổng tiền</th>
                <th>Địa chỉ</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
            </tr>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order['id'] }}</td>
                    <td>{{ $order['created_at'] }}</td>
                    <td>
                        <ul>
                            @foreach($order['items'] as $item)
                                <li>{{ $item['name'] }} (x{{ $item['quantity'] }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ number_format($order['total']) }} VNĐ</td>
                    <td>{{ $order['address'] }}</td>
                    <td>{{ $order['phone'] }}</td>
                    <td>{{ $order['status'] }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Bạn chưa có đơn hàng nào.</p>
    @endif
@endsection
