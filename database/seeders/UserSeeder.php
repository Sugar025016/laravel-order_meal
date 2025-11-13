<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * 執行資料填充。
   */
  public function run(): void
  {
    // 管理員帳號
    User::updateOrCreate(
      ['email' => 'admin@example.com'],
      [
        'name' => '管理員',
        'password' => Hash::make('admin123'), // 可自行修改
        'phone' => '0912345678',
      ]
    );

    // 一般使用者帳號
    User::updateOrCreate(
      ['email' => 'user1@example.com'],
      [
        'name' => '一般使用者1',
        'password' => Hash::make('user1234'),
        'phone' => '0987654321',
      ]
    );
    // 一般使用者帳號
    User::updateOrCreate(
      ['email' => 'user2@example.com'],
      [
        'name' => '一般使用者2',
        'password' => Hash::make('user1234'),
        'phone' => '0987654323',
      ]
    );
    // 一般使用者帳號
    User::updateOrCreate(
      ['email' => 'user3@example.com'],
      [
        'name' => '一般使用者3',
        'password' => Hash::make('user1234'),
        'phone' => '0987654324',
      ]
    );
    // 一般使用者帳號
    User::updateOrCreate(
      ['email' => 'user4@example.com'],
      [
        'name' => '一般使用者4',
        'password' => Hash::make('user1234'),
        'phone' => '0987654325',
      ]
    );
  }
}
