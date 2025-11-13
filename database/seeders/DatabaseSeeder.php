<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      CategorySeeder::class,
      AddressDataSeeder::class,
      UserSeeder::class,
      ShopSeeder::class,
      ShopCategorySeeder::class,
      ProductSeeder::class,
      TabSeeder::class,
      TabProductSeeder::class,
      ScheduleSeeder::class,
      CartShopSeeder::class,
      CartItemSeeder::class,
      // CartSeeder::class
    ]);
  }
}
