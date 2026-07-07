<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // หน้าแสดงยอดเงิน + ประวัติธุรกรรม
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->latest()->paginate(15);

        return view('wallet.index', compact('user', 'transactions'));
    }

    // หน้าฟอร์มเติมเงิน
    public function topupForm()
    {
        return view('wallet.topup');
    }

    // ประมวลผลการเติมเงิน (จำลอง - ยังไม่เชื่อม payment gateway จริง)
    public function topup(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:20|max:50000',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $validated) {
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $validated['amount'];

            // อัปเดตยอดเงินผู้ใช้
            $user->update(['balance' => $balanceAfter]);

            // บันทึกประวัติธุรกรรม
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $validated['amount'],
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'เติมเงินเข้าระบบ',
            ]);
        });

        return redirect()->route('wallet.index')->with('success', 'เติมเงินสำเร็จ! ยอดเงินของคุณอัปเดตแล้ว');
    }
}