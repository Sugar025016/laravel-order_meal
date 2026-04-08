<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Address;


class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'created_at',
    'updated_at',
    'currentAddressRelation',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'phone_verified_at' => 'datetime',
    'password' => 'hashed',
  ];



  // 一對多：User 有多個 Address
  public function addrs()
  {
    return $this->hasMany(Address::class, 'user_id');
  }

  public function shops()
  {
    return $this->hasMany(Shop::class, 'user_id');
  }


  // 關聯：使用 foreign key
  public function currentAddress()
  {
    return $this->belongsTo(Address::class, 'current_address_id');
  }


  public function favoriteShops()
  {
    return $this->belongsToMany(Shop::class, 'favorite_shops')->withTimestamps();
  }

  public function carts()
  {
    return $this->hasMany(CartShop::class);
  }

  public function getCartShopCountAttribute()
  {
    return $this->carts()
      ->distinct('shop_id')
      ->count('shop_id');
  }
}
