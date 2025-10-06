<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'description',
    'price',
    'is_orderable',
    'image_path',
    'shop_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
    'pivot'
  ];



  /**
   * 取得該商品所屬的商店（Shop）。
   */
  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }
}
