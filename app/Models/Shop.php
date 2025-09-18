<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'description',
        'is_orderable',
        'is_open',
        'delivery_km',
        'delivery_price',
        'city',
        'area',
        'street',
        'detail',
        'lat',
        'lng',
        'image',
        'user_id',
    ];

    /**
     * 取得商店的擁有者（User）。
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 取得該商店的所有商品（假設有 Product 關聯）。
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_shop');
    }
}
