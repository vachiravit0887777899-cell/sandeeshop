<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\BoxOpening;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // สรุปยอดรวม
        $totalUsers = User::where('role', 'user')->count();
        $totalBoxes = Box::count();
        $totalOpenings = BoxOpening::count();

        // ยอดขายรวมทั้งหมด (จาก transaction ประเภท purchase)
        $totalRevenue = Transaction::where('type', 'purchase')->sum('amount');

        // ยอดขายวันนี้
        $todayRevenue = Transaction::where('type', 'purchase')
            ->whereDate('created_at', today())
            ->sum('amount');

        // ยอดเติมเงินรวมทั้งหมด
        $totalDeposits = Transaction::where('type', 'deposit')->sum('amount');

        // กล่องสุ่มที่ขายดีที่สุด 5 อันดับ (นับจากจำนวนครั้งที่ถูกเปิด)
        $topBoxes = Box::withCount('openings')
            ->orderByDesc('openings_count')
            ->take(5)
            ->get();

        // กราฟยอดขาย 7 วันล่าสุด
        $salesLast7Days = Transaction::where('type', 'purchase')
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // เติมวันที่ไม่มียอดขายให้เป็น 0 เพื่อให้กราฟแสดงครบ 7 วัน
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->translatedFormat('d M');
            $chartData[] = $salesLast7Days->has($date) ? (float) $salesLast7Days[$date]->total : 0;
        }

        // ผู้ใช้ล่าสุดที่สมัคร
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();

        // ธุรกรรมล่าสุด
        $recentTransactions = Transaction::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalBoxes', 'totalOpenings',
            'totalRevenue', 'todayRevenue', 'totalDeposits',
            'topBoxes', 'chartLabels', 'chartData',
            'recentUsers', 'recentTransactions'
        ));
    }
}