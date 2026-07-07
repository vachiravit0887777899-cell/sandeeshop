<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\BoxItem;
use Illuminate\Http\Request;

class BoxItemController extends Controller
{
    // แสดงไอเทมทั้งหมดในกล่องที่เลือก
    public function index(Box $box)
    {
        $items = $box->items()->latest()->get();
        $totalProbability = $items->sum('probability');

        return view('admin.box_items.index', compact('box', 'items', 'totalProbability'));
    }

    public function create(Box $box)
    {
        return view('admin.box_items.create', compact('box'));
    }

    public function store(Request $request, Box $box)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'rarity' => 'required|in:common,rare,epic,legendary',
            'market_value' => 'required|numeric|min:0',
            'probability' => 'required|numeric|min:0.01|max:100',
            'stock' => 'required|integer|min:0',
        ]);

        // เช็คว่าถ้าเพิ่มไอเทมนี้แล้ว รวม probability จะเกิน 100% ไหม
        $currentTotal = $box->items()->sum('probability');
        if ($currentTotal + $validated['probability'] > 100) {
            return back()->withInput()->withErrors([
                'probability' => "ไม่สามารถเพิ่มได้ เพราะรวม probability จะเกิน 100% (ตอนนี้รวมอยู่ที่ {$currentTotal}%)",
            ]);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('box_items', 'public');
        }

        $box->items()->create($validated);

        return redirect()->route('admin.boxes.items.index', $box)->with('success', 'เพิ่มไอเทมสำเร็จ');
    }

    public function edit(BoxItem $item)
    {
        $box = $item->box;
        return view('admin.box_items.edit', compact('item', 'box'));
    }

    public function update(Request $request, BoxItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'rarity' => 'required|in:common,rare,epic,legendary',
            'market_value' => 'required|numeric|min:0',
            'probability' => 'required|numeric|min:0.01|max:100',
            'stock' => 'required|integer|min:0',
        ]);

        // เช็ค probability รวม โดยไม่นับของตัวเองที่กำลังแก้ไข
        $currentTotal = $item->box->items()->where('id', '!=', $item->id)->sum('probability');
        if ($currentTotal + $validated['probability'] > 100) {
            return back()->withInput()->withErrors([
                'probability' => "ไม่สามารถบันทึกได้ เพราะรวม probability จะเกิน 100% (ไอเทมอื่นรวมอยู่ที่ {$currentTotal}%)",
            ]);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('box_items', 'public');
        }

        $item->update($validated);

        return redirect()->route('admin.boxes.items.index', $item->box)->with('success', 'แก้ไขไอเทมสำเร็จ');
    }

    public function destroy(BoxItem $item)
    {
        $box = $item->box;
        $item->delete();

        return redirect()->route('admin.boxes.items.index', $box)->with('success', 'ลบไอเทมสำเร็จ');
    }
}