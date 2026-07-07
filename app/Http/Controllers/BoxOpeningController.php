<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Services\BoxOpeningService;
use Exception;
use Illuminate\Support\Facades\Auth;

class BoxOpeningController extends Controller
{
    public function __construct(protected BoxOpeningService $boxOpeningService)
    {
    }

    public function open(Box $box)
    {
        try {
            $result = $this->boxOpeningService->open(Auth::user(), $box);

            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $result['box_item']->id,
                    'name' => $result['box_item']->name,
                    'image' => $result['box_item']->image ? asset('storage/' . $result['box_item']->image) : null,
                    'rarity' => $result['box_item']->rarity,
                    'market_value' => $result['box_item']->market_value,
                ],
                'new_balance' => Auth::user()->fresh()->balance,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}