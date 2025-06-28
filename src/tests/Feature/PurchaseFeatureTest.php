<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入すると購入レコードが作成され商品がSOLDになる()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $response = $this->actingAs($user)->post(route('purchase.checkout', ['method' => 'card', 'item' => $item->id]));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面でSOLD表示される()
    {
        $item = Item::factory()->create(['is_sold' => true]);

        $response = $this->get(route('items.index'));

        $response->assertSee('SOLD');
    }

    /** @test */
    public function 購入した商品はプロフィール購入一覧に表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('mypage'));

        $response->assertSee($item->name);
    }
}
