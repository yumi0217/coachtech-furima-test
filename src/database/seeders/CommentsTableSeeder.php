<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = DB::table('users')->pluck('id')->toArray();
        $itemIds = DB::table('items')->pluck('id')->toArray();

        if (empty($userIds) || empty($itemIds)) {
            return; // もしデータがなければ何もしない
        }

        // CommentsTableSeeder.php
        DB::table('comments')->insert([
            [
                'content' => 'とても魅力的な商品ですね！',
                'item_id' => 1, // ItemsTableSeederで作成した商品のID
                'user_id' => 1, // UsersTableSeederで作成したユーザーID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => '状態はどうですか？',
                'item_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
