<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねすると商品に追加され合計数が増える()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('favorites.toggle', ['item' => $item->id]));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいね解除で商品から削除され合計数が減る()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 事前にいいねしておく
        $user->favorites()->attach($item->id);

        // toggleルートで解除（再度押す）
        $this->actingAs($user)
            ->post(route('favorites.toggle', ['item' => $item->id]));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいねアイコンは状態により変化する()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // いいねしていない状態でページ表示（未いいねのアイコン確認）
        $response1 = $this->actingAs($user)->get(route('items.show', $item->id));
        $response1->assertSee('星のアイコン.png');

        // いいね後にページ表示（いいね済みのアイコン確認）
        $user->favorites()->attach($item->id);
        $response2 = $this->actingAs($user)->get(route('items.show', $item->id));
        $response2->assertSee('星のアイコン_黄色.png');
    }
}
