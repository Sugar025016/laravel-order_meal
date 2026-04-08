<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartShop extends Model
{
  use HasFactory;
  protected $fillable = [
    'shop_id',
    'user_id',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
  ];


  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function cartItems()
  {
    return $this->hasMany(CartItem::class);
  }

  public function shop()
  {
    return $this->belongsTo(Shop::class); // 單個產品
  }
}
