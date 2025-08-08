<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Xem danh sách đơn hàng đã đặt
    public function trackOrders()
    {
        $orders = session('orders', []);
        return view('orders.track', compact('orders'));
    }
    
}
