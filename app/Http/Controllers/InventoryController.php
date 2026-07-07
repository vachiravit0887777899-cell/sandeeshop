<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserInventory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Auth::user()->inventories()
            ->with('boxItem')
            ->where('status', 'owned')
            ->latest()
            ->paginate(12);

        return view('inventory.index', compact('inventories'));
    }

    // ขายไอเทมคืนเป็นเงิน (ใช้ market_value เป็นราคารับซื้อคืน)
    public function sell(UserInventory $inventory)
    {
        // เช็คว่าเป็นไอเทมของผู้ใช้คนนี้จริง และยังไม่ถูกขาย/ส่งไปแล้ว
        if ($inventory->user_id !== Auth::id() || $inventory->status !== 'owned') {
            abort(403);
        }

        try {
            DB::transaction(function () use ($inventory) {
                $user = Auth::user();
                $sellPrice = $inventory->boxItem->market_value;

                $balanceBefore = $user->balance;
                $balanceAfter = $balanceBefore + $sellPrice;

                $user->update(['balance' => $balanceAfter]);

                $inventory->update(['status' => 'sold']);

                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'refund',
                    'amount' => $sellPrice,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'description' => "ขายคืนไอเทม: {$inventory->boxItem->name}",
                ]);
            });

            return back()->with('success', 'ขายไอเทมสำเร็จ! เงินเข้ากระเป๋าของคุณแล้ว');
        } catch (Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด กรุณาลองใหม่');
        }
    }
}