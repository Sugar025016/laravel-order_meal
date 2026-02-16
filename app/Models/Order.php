<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  /**
   * 可批次指定的欄位
   */
  protected $fillable = [
    'user_id',
    'shop_id',

    'order_type',
    'delivery_type',
    'status',

    'city',
    'area',
    'street',
    'detail',
    'lat',
    'lng',

    'pay_method',
    'is_cutlery',

    'customer_note',
    'staff_note',

    'subtotal',
    'delivery_fee',
    'total_price',
    'refund_amount',

    'scheduled_time',
    'estimated_delivery_time',
  ];

  /**
   * 型別轉換
   */
  protected $casts = [
    'is_cutlery' => 'boolean',

    'lat' => 'float',
    'lng' => 'float',

    'scheduled_time' => 'datetime',
    'estimated_delivery_time' => 'datetime',
  ];

  /* =====================
     |  關聯 Relationships
     |===================== */

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }

  /* =====================
     |  常用狀態常數（推薦）
     |===================== */

  public const STATUS_PENDING   = 1;
  public const STATUS_ACCEPTED  = 2;
  public const STATUS_COOKING   = 3;
  public const STATUS_DELIVERING = 4;
  public const STATUS_COMPLETED = 5;
  public const STATUS_CANCELED  = 6;
  public const STATUS_PROBLEM   = 7;


  public const ONGOING_STATUSES = [
    self::STATUS_PENDING,
    self::STATUS_ACCEPTED,
    self::STATUS_COOKING,
    self::STATUS_DELIVERING,
    self::STATUS_PROBLEM,
  ];

  public const FINISHED_STATUSES = [
    self::STATUS_COMPLETED,
    self::STATUS_CANCELED,
  ];

  public function scopeOngoing($query)
  {
    return $query->whereIn('status', self::ONGOING_STATUSES);
  }

  public function scopeHistory($query)
  {
    return $query->whereIn('status', self::FINISHED_STATUSES);
  }
}
