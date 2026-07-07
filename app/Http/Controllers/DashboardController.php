<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $inventoryCount = $user->inventories()->where('status', 'owned')->count();
        $totalOpenings = $user->boxOpenings()->count();

        $rareItemsCount = $user->boxOpenings()
            ->whereHas('boxItem', function ($q) {
                $q->whereIn('rarity', ['epic', 'legendary']);
            })->count();

        $totalSpent = $user->transactions()->where('type', 'purchase')->sum('amount');

        $recentOpenings = $user->boxOpenings()
            ->with(['box', 'boxItem'])
            ->latest()
            ->take(5)
            ->get();

        $recentItems = $user->inventories()
            ->with('boxItem')
            ->where('status', 'owned')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'user', 'inventoryCount', 'totalOpenings',
            'rareItemsCount', 'totalSpent', 'recentOpenings', 'recentItems'
        ));
    }
}