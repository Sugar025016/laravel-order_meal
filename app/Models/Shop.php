<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'brand',              // 品牌名稱
    'branch',             // 分店名稱
    'phone',              // 聯絡電話
    'description',        // 店家描述 / 簡介

    'is_orderable',       // 是否可下單
    'is_open',            // 是否營業中

    'delivery_km',        // 外送距離（公里）
    'delivery_price',     // 外送費用

    'phone_verified_at',  // 電話驗證時間
    'city',               // 縣市
    'area',               // 區域
    'street',             // 街道
    'detail',             // 詳細地址（樓層、門牌等）

    'lat',                // 緯度
    'lng',                // 經度

    'image_path',         // 店家圖片路徑
    'user_id',            // 所屬使用者 ID
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
    'addressData',
    'categories.pivot',
  ];


  /**
   * 取得商店的擁有者（User）。
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function fullAddress()
  {
    return "{$this->city}{$this->area}{$this->street}";
  }



  // public function getCityAttribute()
  // {
  //   return $this->addressData?->city;
  // }

  // public function getAreaAttribute()
  // {
  //   return $this->addressData?->area;
  // }

  // public function getStreetAttribute()
  // {
  //   return $this->addressData?->street;
  // }

  // 可選：完整地址
  // public function getFullAddressAttribute()
  // {
  //   return "{$this->city}{$this->area}{$this->street}{$this->detail}";
  // }

  // public function getCategoryAttribute()
  // {
  //   // 回傳分類名稱陣列
  //   return $this->categories->makeHidden('pivot');
  // }

  // public function categories()
  // {
  //   return $this->belongsToMany(Category::class, 'category_shop')
  //     ->select('categories.id', 'categories.name'); // 只取必要欄位
  // }
  public function categories()
  {
    // 一間店有多個分類（例如餐廳 -> 飲料、甜點）
    return $this->belongsToMany(Category::class, 'shop_category', 'shop_id', 'category_id')
      ->withTimestamps();
    // ↑ 如果你的中介表名稱或欄位不同，要改這裡
  }

  // Accessor
  // public function getProductsAttribute()
  // {
  //   return $this->products()->get(); // 或者 ->all()，取所有商品
  // }

  public function products()
  {
    return $this->hasMany(Product::class);
  }


  public function tabs()
  {
    return $this->hasMany(Tab::class);
  }

  public function fans()
  {
    return $this->belongsToMany(User::class, 'favorite_shops')->withTimestamps();
  }
  public function schedules()
  {
    return $this->hasMany(Schedule::class);
  }
}
