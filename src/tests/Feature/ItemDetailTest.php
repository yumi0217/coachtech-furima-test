<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細ページに商品情報とコメントが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'description' => '商品の状態は良好です。購入後、即発送いたします。',
            'condition' => '良好',
        ]);

        $commentUser = User::factory()->create(['name' => 'コメント太郎']);
        $item->comments()->create([
            'user_id' => $commentUser->id,
            'content' => 'とても良い商品でした！',
        ]);

        $response = $this->get(route('items.show', $item->id));

        $response->assertSeeText('テスト商品');
        $response->assertSeeText('テストブランド');
        $response->assertSeeText('¥12,345');
        $response->assertSeeText('商品の状態は良好です。');
        $response->assertSeeText('購入後、即発送いたします。');
        $response->assertSeeText('良好');
        $response->assertSeeText('コメント太郎');
        $response->assertSeeText('とても良い商品でした！');
    }

    /** @test */
    public function 商品詳細ページに複数のカテゴリが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $category1 = Category::create(['name' => '家電']);
        $category2 = Category::create(['name' => 'ガジェット']);
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get(route('items.show', $item->id));

        $response->assertSeeText('家電');
        $response->assertSeeText('ガジェット');
    }
}
