<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $products;

    public function __construct()
    {
        $this->products = [
            1 => (object)[
                'id' => 1,
                'name' => 'Áo hoodie',
                'price' => 350000,
                'image' => 'hoodie.jpg',
                'description' => 'Áo hoodie chất liệu nỉ bông, giữ ấm tốt và thoải mái.',
                'stock' => 10
            ],
            2 => (object)[
                'id' => 2,
                'name' => 'Giày sneaker',
                'price' => 800000,
                'image' => 'giay.jpg',
                'description' => 'Giày sneaker thời trang, thoáng khí, thích hợp đi học và đi chơi.',
                'stock' => 20
            ],
            3 => (object)[
                'id' => 3,
                'name' => 'Áo phông',
                'price' => 200000,
                'image' => 'shirt.jpg',
                'description' => 'Áo phông cotton 100%, mềm mại và dễ chịu.',
                'stock' => 50
            ],
        ];
    }

    public function index()
    {
        return view('products.index', ['products' => $this->products]);
    }

    public function show($id)
    {
        $product = $this->products[$id] ?? null;

        if (!$product) {
            abort(404);
        }

        $reviews = session("reviews.{$id}", []);
        return view('products.show', compact('product', 'reviews'));
    }

    public function addReview(Request $request, $id)
    {
        $request->validate([
            'author' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        $newReview = [
            'author' => $request->input('author'),
            'content' => $request->input('content'),
        ];

        $reviews = session("reviews.{$id}", []);
        $reviews[] = $newReview;

        session(["reviews.{$id}" => $reviews]);

        return redirect()->route('products.show', $id)->with('success', 'Đánh giá đã được gửi!');
    }
}
