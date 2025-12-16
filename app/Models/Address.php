<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use HasFactory;

  protected $table = 'addresses';

  protected $fillable = [
    'city',
    'area',
    'street',
    'detail',
    'lat',
    'lng',
    'user_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
