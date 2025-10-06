<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
  use HasFactory;
  protected $fillable = [
    'week',
    'start_time',
    'end_time',
    'shop_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  /**
   * 關聯：Schedule 屬於一間店（Shop）
   */
  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  protected static function booted()
  {
    static::creating(function ($schedule) {
      $count = self::where('shop_id', $schedule->shop_id)
        ->where('week', $schedule->week)
        ->count();

      if ($count >= 3) {
        throw new \Exception('同一店鋪的該星期排程已達上限（最多3筆）');
      }
    });
  }
}
