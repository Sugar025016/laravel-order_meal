<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'shop_id',
    'status',
    'payment_method',
    'phone',
    'remark',
    'take_time',
    'take_remark',
    'city',
    'area',
    'street',
    'detail',
    'lat',
    'lng',
  ];

  // 狀態常數
  const STATUS_PENDING = 'pending';
  const STATUS_PAID = 'paid';
  const STATUS_DELIVERED = 'delivered';
  const STATUS_CANCELLED = 'cancelled';

  // 付款方式常數
  const PAYMENT_CASH = 'cash';
  const PAYMENT_CREDIT = 'credit';
  const PAYMENT_LINEPAY = 'linepay';

  // 狀態選單
  public static function getStatusMenu(): array
  {
    return [
      self::STATUS_PENDING => '待付款',
      self::STATUS_PAID => '已付款',
      self::STATUS_DELIVERED => '已送達',
      self::STATUS_CANCELLED => '已取消',
    ];
  }

  // 付款方式選單
  public static function getPaymentMenu(): array
  {
    return [
      self::PAYMENT_CASH => '現金',
      self::PAYMENT_CREDIT => '信用卡',
      self::PAYMENT_LINEPAY => 'LINE Pay',
    ];
  }

  /**
   * 取得該商品所屬的商店（Shop）。
   */
  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function orderDetails()
  {
    return $this->hasMany(OrderDetail::class);
  }
}
