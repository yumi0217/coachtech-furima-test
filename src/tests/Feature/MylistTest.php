<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    protected function tearDown(): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        parent::tearDown();
    }

    public function test_いいねした商品だけが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

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

        $response = $this->actingAs($user)->get('/test-mylist');

        $response->assertSeeText('いいね商品');
        $response->assertDontSeeText('いいねしてない商品');
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

        $response = $this->actingAs($user)->get('/test-mylist');
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

        $response = $this->actingAs($user)->get('/test-mylist');
        $response->assertDontSeeText('自分の商品');
    }

    public function test_未認証ユーザーはマイリストが空になる()
    {
        // 前処理：データ作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name' => 'いいね商品',
            'user_id' => $user->id,
        ]);
        $user->favorites()->attach($item->id);

        // 未ログインでアクセス
        $response = $this->get('/test-mylist');

        // 🔽 レスポンスの内容確認（リダイレクトされてないかチェック）
        $response->assertStatus(200); // ← ここ追加

        // 表示内容の確認
        $response->assertSeeText('まだ商品がありません。');
        $response->assertDontSeeText('いいね商品');
    }
}
