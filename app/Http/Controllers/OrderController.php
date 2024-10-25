<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order()
    {
        // Define tabs
        $tabs = [
            ['title' => 'All', 'id' => 'all', 'category' => 'all'],
            ['title' => 'Drinks', 'id' => 'drinks', 'category' => 'drink'],
            ['title' => 'Food', 'id' => 'food', 'category' => 'food'],
            ['title' => 'Food', 'id' => 'food', 'category' => 'food'],
        ];

        $orderItems = [
            ['id' => 1, 'name' => 'Cokeasdasdasdasdkjabsdkjabsdjasbdkjasbdkjasnxkajlshdbasdj', 'price' => 30, 'category' => 'drink', 'image' => 'https://www.coca-cola.com/content/dam/onexp/ph/en/brands/coca-cola/ph-coca-cola-classic.png'],
            ['id' => 2, 'name' => 'Pepsi', 'price' => 35, 'category' => 'drink', 'image' => 'https://static.vecteezy.com/system/resources/previews/036/576/233/non_2x/bottle-of-pepsi-cola-isolated-free-png.png'],
            ['id' => 3, 'name' => 'Burger', 'price' => 50, 'category' => 'food', 'image' => 'https://static.vecteezy.com/system/resources/previews/022/911/694/non_2x/cute-cartoon-burger-icon-free-png.png'],
            ['id' => 4, 'name' => 'Fries', 'price' => 20, 'category' => 'food', 'image' => 'https://static.vecteezy.com/system/resources/thumbnails/024/508/765/small_2x/french-fries-isolated-on-background-with-generative-ai-png.png'],
            ['id' => 5, 'name' => 'Chicken', 'price' => 60, 'category' => 'food', 'image' => 'https://pngimg.com/d/fried_chicken_PNG14097.png'],
        ];

        return view('sales.checkout', compact('tabs', 'orderItems'));
    }
}
