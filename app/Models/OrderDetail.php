<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_name',
    'remark',
    'qty',
    'product_price',
    'product_id',
    'order_id',
  ];
  // 隱藏 created_at 和 updated_at
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
