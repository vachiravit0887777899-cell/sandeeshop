<?php

namespace App\Services;

use App\Models\Box;
use App\Models\BoxOpening;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInventory;
use Exception;
use Illuminate\Support\Facades\DB;

class BoxOpeningService
{
    /**
     * เปิดกล่องสุ่มให้ผู้ใช้ 1 ใบ
     * คืนค่าเป็น array ['box_item' => BoxItem, 'opening' => BoxOpening]
     *
     * @throws Exception ถ้าเงินไม่พอ, กล่องหมด, หรือไม่มีไอเทมให้สุ่ม
     */
    public function open(User $user, Box $box): array
    {
        return DB::transaction(function () use ($user, $box) {

            // ล็อกแถวเพื่อป้องกันการเปิดพร้อมกันหลายครั้งจนสต็อกติดลบ (race condition)
            $box = Box::where('id', $box->id)->lockForUpdate()->firstOrFail();
            $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            if ($box->status !== 'active') {
                throw new Exception('กล่องสุ่มนี้ปิดการขายแล้ว');
            }

            if ($box->stock <= 0) {
                throw new Exception('กล่องสุ่มนี้หมดสต็อกแล้ว');
            }

            if ($user->balance < $box->price) {
                throw new Exception('ยอดเงินในกระเป๋าไม่เพียงพอ กรุณาเติมเงิน');
            }

            // ดึงไอเทมที่ยังมีสต็อกเหลือเท่านั้น
            $availableItems = $box->items()->where('stock', '>', 0)->get();

            if ($availableItems->isEmpty()) {
                throw new Exception('ไม่มีไอเทมเหลือให้สุ่มในกล่องนี้');
            }

            // สุ่มไอเทมตาม probability
            $selectedItem = $this->pickRandomItem($availableItems);

            // หักเงินผู้ใช้
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore - $box->price;
            $user->update(['balance' => $balanceAfter]);

            // หักสต็อกกล่องและไอเทม
            $box->decrement('stock');
            $selectedItem->decrement('stock');

            // บันทึกประวัติการเปิดกล่อง
            $opening = BoxOpening::create([
                'user_id' => $user->id,
                'box_id' => $box->id,
                'box_item_id' => $selectedItem->id,
            ]);

            // เพิ่มไอเทมเข้าคลังผู้ใช้
            UserInventory::create([
                'user_id' => $user->id,
                'box_item_id' => $selectedItem->id,
                'status' => 'owned',
            ]);

            // บันทึกธุรกรรมทางการเงิน
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => $box->price,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "เปิดกล่องสุ่ม: {$box->name}",
            ]);

            return [
                'box_item' => $selectedItem,
                'opening' => $opening,
            ];
        });
    }

    /**
     * สุ่มไอเทม 1 ชิ้นจาก collection ตามค่า probability ของแต่ละไอเทม
     */
    private function pickRandomItem($items)
    {
        $totalProbability = $items->sum('probability');

        // สุ่มเลขระหว่าง 0 ถึง totalProbability (คูณ 100 เพื่อความละเอียดทศนิยม 2 ตำแหน่ง)
        $random = mt_rand(0, (int) ($totalProbability * 100)) / 100;

        $cumulative = 0;
        foreach ($items as $item) {
            $cumulative += $item->probability;
            if ($random <= $cumulative) {
                return $item;
            }
        }

        // fallback เผื่อกรณีปัดเศษพลาด (ไม่ควรเกิดขึ้นถ้า probability รวมถูกต้อง)
        return $items->last();
    }
}