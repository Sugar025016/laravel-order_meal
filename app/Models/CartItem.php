<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  use HasFactory;
  protected $fillable = [
    'cart_shop_id',
    'product_id',
    'qty',
    'remark',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
  ];


  public function cartShop()
  {
    return $this->belongsTo(CartShop::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
