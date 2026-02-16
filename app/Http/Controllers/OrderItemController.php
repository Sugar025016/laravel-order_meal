<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    /**
     * 取得某筆訂單的餐點明細
     */
    public function index($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        return response()->json([
            'data' => $order->items
        ]);
    }

    /**
     * 更新餐點異常（缺貨 / 損壞 / 備註）
     * 店家或客服使用
     */
    public function update(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);

        $validated = $request->validate([
            'missing_qty' => 'nullable|integer|min:0',
            'damaged_qty' => 'nullable|integer|min:0',
            'staff_note'  => 'nullable|string',
        ]);

        // 數量防呆
        $missing = $validated['missing_qty'] ?? $item->missing_qty;
        $damaged = $validated['damaged_qty'] ?? $item->damaged_qty;

        if ($missing + $damaged > $item->quantity) {
            return response()->json([
                'message' => '缺貨與損壞數量不可超過原始數量'
            ], 422);
        }

        DB::transaction(function () use ($item, $validated, $missing, $damaged) {
            $item->update([
                'missing_qty'   => $missing,
                'damaged_qty'   => $damaged,
                'staff_note'    => $validated['staff_note'] ?? $item->staff_note,
                'refund_amount' => ($missing + $damaged) * $item->product_price,
            ]);

            // 同步更新 order 的總退款金額
            $order = $item->order;
            $order->refund_amount = $order->items()->sum('refund_amount');
            $order->save();
        });

        return response()->json([
            'message' => '餐點異常已更新',
            'data'    => $item->fresh()
        ]);
    }

    /**
     * 客戶回報餐點問題（例如送錯、漏送）
     */
    public function customerReport(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);

        $validated = $request->validate([
            'customer_note' => 'required|string|max:500',
        ]);

        $item->update([
            'customer_note' => $validated['customer_note'],
        ]);

        // 將訂單標記為問題訂單（status = 7）
        $order = $item->order;
        if ($order->status !== 7) {
            $order->status = 7;
            $order->save();
        }

        return response()->json([
            'message' => '已回報餐點問題，客服將協助處理'
        ]);
    }
}
