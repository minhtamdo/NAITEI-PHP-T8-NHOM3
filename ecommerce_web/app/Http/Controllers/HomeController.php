<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = [
            (object)['id' => 1, 'name' => 'Áo hoodie'],
            (object)['id' => 2, 'name' => 'Giày sneaker'],
            (object)['id' => 3, 'name' => 'Áo phông'],
        ];

        return view('home', compact('products'));
    }
}
