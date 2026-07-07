<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Box::with('category')->where('status', 'active')->where('stock', '>', 0);

        // กรองตามหมวดหมู่ (ถ้ามีการเลือก)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $boxes = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('shop.index', compact('boxes', 'categories'));
    }

    public function show(Box $box)
    {
        // โหลดไอเทมทั้งหมดในกล่อง เรียงตาม rarity (แพงสุดโชว์ก่อน)
        $box->load(['category', 'items' => function ($query) {
            $query->orderByRaw("
                CASE rarity
                    WHEN 'legendary' THEN 1
                    WHEN 'epic' THEN 2
                    WHEN 'rare' THEN 3
                    WHEN 'common' THEN 4
                END
            ");
        }]);

        return view('shop.show', compact('box'));
    }
}