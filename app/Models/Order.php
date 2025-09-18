<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phone',
        'remark',
        'take_remark',
        'city',
        'area',
        'street',
        'detail',
        'lat',
        'lng',
        'take_time',
        'created_at',
        'shop_id',
        'user_id',
    ];

    /**
     * 取得該商品所屬的商店（Shop）。
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
