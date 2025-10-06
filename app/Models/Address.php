<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
  use HasFactory;
  protected $table = 'addresses'; // 對應資料表名稱
  protected $fillable = [
    'address_data_id',
    'detail',
    'lat',
    'lng',
    'user_id',
  ];

  // 隱藏關聯欄位
  protected $hidden = [
    'created_at',
    'updated_at',
    'addressData'
  ];


  public function user()
  {
    return $this->belongsTo(User::class);
  }


  // Accessor：合併 address_data 的欄位
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
}
