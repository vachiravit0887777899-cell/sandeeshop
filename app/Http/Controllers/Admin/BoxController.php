<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BoxController extends Controller
{
    public function index()
    {
        $boxes = Box::with('category')->latest()->paginate(10);
        return view('admin.boxes.index', compact('boxes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.boxes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();

        if ($request->hasFile('image')) {
    $validated['image'] = \App\Services\CloudinaryUploadService::upload($request->file('image'), 'boxes');
}

        Box::create($validated);

        return redirect()->route('admin.boxes.index')->with('success', 'สร้างกล่องสุ่มสำเร็จ');
    }

    public function edit(Box $box)
    {
        $categories = Category::all();
        return view('admin.boxes.edit', compact('box', 'categories'));
    }

    public function update(Request $request, Box $box)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('boxes', 'public');
        }

        $box->update($validated);

        return redirect()->route('admin.boxes.index')->with('success', 'แก้ไขกล่องสุ่มสำเร็จ');
    }

    public function destroy(Box $box)
    {
        $box->delete();
        return redirect()->route('admin.boxes.index')->with('success', 'ลบกล่องสุ่มสำเร็จ');
    }
}