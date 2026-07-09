<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBoxes = Box::with('category')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();

        return view('welcome', compact('featuredBoxes', 'categories'));
    }
}