<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CheckoutRequest;


class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng.');
        }

        // Lấy tất cả sản phẩm trong giỏ hàng của user, kèm thông tin product
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        // Tính subtotal
        $subtotal = $cartItems->sum(function($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        $shipping = 30000; // phí vận chuyển mặc định

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping'));
    }

    public function add(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            // Nếu chưa đăng nhập, chuyển hướng tới login
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm vào giỏ hàng.');
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($cartItem) {
            // Nếu đã có, tăng số lượng
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem->update(['quantity' => $request->quantity]);
        return response()->json(['success' => true]);
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return response()->json(['success' => true]);
    }
    
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();
        $subtotal = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        $shipping = 30000;

        return view('cart.checkout', compact('cartItems', 'subtotal', 'shipping'));
    }

    public function processCheckout(CheckoutRequest $request)
    {
        DB::transaction(function() use ($request) {
            // 1. Tạo address
            $fullName = $request->firstName . ' ' . $request->lastName;
            $address = Address::create([
                'user_id'      => Auth::id(),
                'full_name'    => $fullName,
                'phone_number' => $request->phone_number,
                'details'      => $request->details,
                'ward'         => $request->ward,
                'district'     => $request->district,
                'city'         => $request->city,
                'postal_code'  => $request->postal_code,
                'country'      => $request->country,
                'is_default'   => 1,
            ]);

            // 2. Lấy giỏ hàng
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \InvalidArgumentException('Giỏ hàng trống.');
            }

            // 3. Tạo order
            $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

            $order = Order::create([
                'user_id'    => Auth::id(),
                'address_id' => $address->id,
                'total_price'=> $totalPrice,
                'status'     => Order::STATUS_PENDING,
            ]);

            // 4. Tạo order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                ]);
            }

            // 5. Xóa giỏ hàng
            CartItem::where('user_id', Auth::id())->delete();

            $product = $item->product;
            if ($product->stock < $item->quantity) {
                throw new \Exception("Sản phẩm {$product->name} không đủ số lượng trong kho.");
            }
            $product->decrement('stock', $item->quantity);
        });

        return redirect(route('home'))->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ bạn trong vòng 48h để xác nhận đơn hàng.');
    }
}
