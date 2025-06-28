<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品一覧が表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(); // ログインユーザー
        $otherUser = User::factory()->create();

        // 他人の出品商品（表示されるべき）
        Item::factory()->create([
            'name' => '表示される商品',
            'user_id' => $otherUser->id,
            'is_sold' => false,
        ]);

        // 自分の出品商品（表示されない）
        Item::factory()->create([
            'name' => '非表示の商品',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/');

        // 🔽 ここで保存
        file_put_contents(storage_path('app/test-output.html'), $response->getContent());

        $response->assertStatus(200);
        $response->assertSeeText('表示される商品');
        $response->assertDontSee('非表示の商品');
    }


    public function test_購入済み商品にはSOLDが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Item::factory()->create([
            'name' => 'SOLD商品',
            'user_id' => $otherUser->id,
            'is_sold' => true,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertSee('SOLD');
    }
}
