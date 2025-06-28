<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodDisplayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法選択画面に初期支払い方法が表示されている()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('purchases.create', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('支払い方法');
        $response->assertSee('コンビニ支払い');
        $response->assertSee('カード支払い');
        $response->assertSee('未選択');
    }
}
