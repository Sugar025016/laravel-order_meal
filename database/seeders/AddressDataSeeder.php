<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressDataSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // $json = file_get_contents(database_path('data/address.sql'));
    // $data = json_decode($json, true);

    // DB::table('address_data')->insert($data);
    $path = database_path('data/address_data.sql');
    $sql = file_get_contents($path);
    \DB::statement("SET NAMES 'utf8mb4'");
    \DB::unprepared($sql);

    $this->command->info('address_data 匯入成功！');
  }
}
