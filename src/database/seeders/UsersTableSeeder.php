<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Taro Yamada',
                'email' => 'taro@example.com',
                'password' => Hash::make('password'),
                'profile_image' => 'taro.png',
            ],
            [
                'name' => 'Hanako Tanaka',
                'email' => 'hanako@example.com',
                'password' => Hash::make('password'),
                'profile_image' => null,
            ],
            [
                'name' => 'Ichiro Suzuki',
                'email' => 'ichiro@example.com',
                'password' => Hash::make('password'),
                'profile_image' => 'ichiro.png',
            ],
            [
                'name' => 'Sakura Sato',
                'email' => 'sakura@example.com',
                'password' => Hash::make('password'),
                'profile_image' => 'sakura.png',
            ],
            [
                'name' => 'Kenji Kato',
                'email' => 'kenji@example.com',
                'password' => Hash::make('password'),
                'profile_image' => null,
            ],
            [
                'name' => 'miyo',
                'email' => 'miyo.test3@gmail.com',
                'password' => Hash::make('password'),
                'profile_image' => 'miyo.png',
            ],

            [
                'name' => 'Lana',
                'email' => 'lana.test@gmail.com',
                'password' => Hash::make('password'),
                'profile_image' => 'lana_s.jpg',
            ],

            // ✅ テスト用ログインユーザー（出品者）
            [
                'name' => 'Test Seller',
                'email' => 'seller@example.com',
                'password' => Hash::make('password'),
                'profile_image' => 'seller.png',
            ],
            // ✅ テスト用ログインユーザー（購入者）
            [
                'name' => 'Test Buyer',
                'email' => 'buyer@example.com',
                'password' => Hash::make('password'),
                'profile_image' => 'buyer.png',
            ],
        ]);
    }
}
