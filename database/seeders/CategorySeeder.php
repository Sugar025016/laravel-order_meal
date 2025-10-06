<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('categories')->insert([
      [
        'name' => '飲料',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => '主食',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => '甜點',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => '小吃',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => '火鍋',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => '其他',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ]);
  }
}
