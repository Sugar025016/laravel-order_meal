<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
  ];

  // 隱藏關聯欄位
  protected $hidden = [
    'pivot',
    'created_at',
    'updated_at',
  ];


  /**
   * 取得該商品所屬的商店（Shop）。
   */
  public function shops()
  {
    return $this->belongsToMany(Shop::class, 'shop_category');
  }
}
