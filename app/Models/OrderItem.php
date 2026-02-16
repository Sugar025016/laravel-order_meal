<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    /**
     * 可大量指派欄位
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'missing_qty',
        'damaged_qty',
        'subtotal',
        'refund_amount',
        'customer_note',
        'staff_note',
    ];

    /**
     * 型別轉換
     */
    protected $casts = [
        'product_price' => 'integer',
        'quantity'      => 'integer',
        'missing_qty'   => 'integer',
        'damaged_qty'   => 'integer',
        'subtotal'      => 'integer',
        'refund_amount' => 'integer',
    ];

    /* =========================
     |  Relationships
     ========================= */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /* =========================
     |  Business Logic
     ========================= */

    /**
     * 實際出貨數量
     */
    public function shippedQty(): int
    {
        return max(
            0,
            $this->quantity - $this->missing_qty - $this->damaged_qty
        );
    }

    /**
     * 是否有任何異常（缺貨或損壞）
     */
    public function hasIssue(): bool
    {
        return $this->missing_qty > 0 || $this->damaged_qty > 0;
    }

    /**
     * 計算建議退款金額（單品）
     *（可用於 service 層）
     */
    public function calculateRefundAmount(): int
    {
        $problemQty = $this->missing_qty + $this->damaged_qty;

        return $problemQty * $this->product_price;
    }
}
