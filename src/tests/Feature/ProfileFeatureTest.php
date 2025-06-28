<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィールページにユーザー情報と出品商品と購入商品が表示される()
    {
        // ユーザー作成
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        // 出品商品を3件作成（自分の商品）
        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // 別ユーザーの商品を購入しておく
        $otherItem = Item::factory()->create(); // 出品者は別
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $otherItem->id,
            'purchased_at' => now(),
        ]);

        // マイページにアクセス
        $response = $this->actingAs($user)->get(route('mypage'));

        // ユーザー名が表示される
        $response->assertSee('テストユーザー');

        // 出品した商品名が表示される
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }

        // 購入した商品名が表示される
        $response->assertSee($otherItem->name);
    }
}
