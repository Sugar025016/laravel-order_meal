<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressData extends Model
{
  use HasFactory;
  protected $table = 'address_data'; // 對應資料表名稱
  protected $fillable = [
    'city',
    'area',
    'street',
  ];
}
