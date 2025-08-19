<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = [
            [
                'id' => 1,
                'name' => 'Sách Laravel cơ bản',
                'price' => 150000,
                'quantity' => 2,
                'image' => 'https://via.placeholder.com/100x120.png?text=Laravel'
            ],
            [
                'id' => 2,
                'name' => 'Áo Hoodie',
                'price' => 350000,
                'quantity' => 1,
                'image' => 'https://via.placeholder.com/100x120.png?text=Hoodie'
            ]
        ];

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);

        $cart[] = [
            'name' => $request->name,
            'price' => $request->price,
            'image' => $request->image,
            'quantity' => $request->quantity,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request, $index)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$index])) {
            $cart[$index]['quantity'] = max(1, (int)$request->quantity); // Không cho < 1
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cập nhật số lượng thành công!');
    }

    public function remove($index)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            session()->put('cart', array_values($cart)); // reset index
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm!');
    }
    
    public function checkout()
{
    // Lấy giỏ hàng từ session
    $cart = session()->get('cart', []);

    // Tính tổng tiền
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Tạm thời dùng dữ liệu giả cho địa chỉ và SĐT
    // Sau này có thể lấy từ DB nếu có bảng địa chỉ
    $addresses = [
        '123 Đường ABC, Quận 1, TP. HCM',
        '456 Đường DEF, Quận 3, TP. HCM',
    ];

    $phones = [
        '0901234567',
        '0987654321',
    ];

    return view('cart.checkout', compact('cart', 'total', 'addresses', 'phones'));
}


    public function processCheckout(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        // Lấy địa chỉ & số điện thoại
        $address = $request->input('selected_address') === 'new'
            ? $request->input('new_address')
            : $request->input('selected_address');

        $phone = $request->input('selected_phone') === 'new'
            ? $request->input('new_phone')
            : $request->input('selected_phone');

        $order = [
            'id' => uniqid('order_'),
            'items' => $cart,
            'total' => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)),
            'address' => $address,
            'phone' => $phone,
            'status' => 'Đang xử lý',
            'created_at' => now()->format('Y-m-d H:i:s')
        ];

        $orders = session('orders', []);
        $orders[] = $order;
        session(['orders' => $orders]);

        session()->forget('cart');

        return redirect()->route('orders.track')->with('success', 'Đặt hàng thành công! Mã đơn: ' . $order['id']);
    }
}
