<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(); // ←ここで赤波線が出る場合のコメント必須！

        $otherUser = User::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => 'いいね商品',
            'user_id' => $otherUser->id,
        ]);

        $notLikedItem = Item::factory()->create([
            'name' => 'いいねしてない商品',
            'user_id' => $otherUser->id,
        ]);

        $user->favorites()->attach($likedItem->id);

        // 🔽 テスト専用ルートへアクセス
        $response = $this->actingAs($user)->get('/test-mylist');

        $response->assertSeeText('いいね商品');
        $response->assertDontSeeText('いいねしてない商品'); // これで通るようになる
    }




    public function test_購入済み商品にはSOLDが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $soldItem = Item::factory()->create([
            'name' => 'SOLDアイテム',
            'user_id' => $seller->id,
            'is_sold' => true,
        ]);

        $user->favorites()->attach($soldItem->id);

        $response = $this->actingAs($user)->get('/');
        $response->assertSeeText('SOLD');
    }

    public function test_自分が出品した商品は表示されない()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $ownItem = Item::factory()->create([
            'name' => '自分の商品',
            'user_id' => $user->id,
        ]);

        $user->favorites()->attach($ownItem->id);

        $response = $this->actingAs($user)->get('/');
        $response->assertDontSeeText('自分の商品');
    }

    public function test_未認証ユーザーはマイリストが空になる()
    {
        $response = $this->get('/');

        // 「まだ商品がありません。」が表示されるかどうか確認
        $response->assertSeeText('まだ商品がありません。');

        // マイリストに本来見えるはずのアイテム名などが見えていないかチェックしてもOK
        $response->assertDontSeeText('いいね商品');
    }
}
