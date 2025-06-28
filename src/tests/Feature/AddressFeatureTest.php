<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;

class AddressFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_登録した住所が購入画面に反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都千代田区1-1-1',
            'building' => '皇居前ビル',
        ]);

        $response = $this->actingAs($user)->get(route('purchases.create', ['item_id' => $item->id]));

        $response->assertSee('123-4567');
        $response->assertSee('東京都千代田区1-1-1');
        $response->assertSee('皇居前ビル');
    }
    /** @test */
    public function 購入に送付先住所が紐づく()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $address = Address::create([
            'user_id'     => $user->id,
            'postal_code' => '987-6543',
            'address'     => '大阪府大阪市中央区2-2-2',
            'building'    => 'なんばタワー',
        ]);

        $response = $this->actingAs($user)->post(route('purchase.checkout', [
            'method' => 'card',
            'item' => $item->id,
        ]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 紐づけを確認する（purchaseとaddressが正しく存在）
        $this->assertTrue($user->address->is($address));
    }
}
