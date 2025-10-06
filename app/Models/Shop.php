<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'brand',
    'branch',
    'phone',
    'description',
    'is_orderable',
    'is_open',
    'delivery_km',
    'delivery_price',
    'phone_verified_at',
    'address_data_id',
    'detail',
    'lat',
    'lng',
    'image_path',
    'user_id',
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
  // Accessor：合併 address_data 的欄位
  // protected $appends = ['city', 'area', 'street', 'category', 'products'];
  protected $appends = ['city', 'area', 'street'];
  public function fullAddress()
  {
    return "{$this->city}{$this->area}{$this->street}";
  }

  public function addressData()
  {
    return $this->belongsTo(AddressData::class, 'address_data_id');
  }


  public function getCityAttribute()
  {
    return $this->addressData?->city;
  }

  public function getAreaAttribute()
  {
    return $this->addressData?->area;
  }

  public function getStreetAttribute()
  {
    return $this->addressData?->street;
  }

  // 可選：完整地址
  public function getFullAddressAttribute()
  {
    return "{$this->city}{$this->area}{$this->street}{$this->detail}";
  }

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
}
