<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'city',
        'area',
        'street',
        'detail',
        'lat',
        'lng',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
