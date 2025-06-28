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
    public function 商品詳細ページに必要な情報が表示される()
    {
        // 出品者と商品作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'description' => '商品の状態は良好です。購入後、即発送いたします。', // ←ビュー側の表示に合わせる
            'condition' => '良好',
        ]);


        // カテゴリ作成（複数）
        $category1 = Category::create(['name' => '家電']);
        $category2 = Category::create(['name' => 'ガジェット']);
        $item->categories()->attach([$category1->id, $category2->id]);


        // コメント作成
        $commentUser = User::factory()->create(['name' => 'コメント太郎']);
        $item->comments()->create([
            'user_id' => $commentUser->id,
            'content' => 'とても良い商品でした！',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get(route('items.show', $item->id));

        // 商品情報の各要素が含まれていることを確認
        $response->assertSeeText('テスト商品');                  // 商品名
        $response->assertSeeText('テストブランド');              // ブランド名
        $response->assertSeeText('¥12,345');                     // 価格（フォーマットに合わせる）
        $response->assertSeeText('商品の状態は良好です。');          
        $response->assertSeeText('購入後、即発送いたします。');
        $response->assertSeeText('良好');                         // 商品状態
        $response->assertSeeText('家電');                         // カテゴリ
        $response->assertSeeText('ガジェット');                   // カテゴリ
        $response->assertSeeText('コメント太郎');                 // コメントユーザー名
        $response->assertSeeText('とても良い商品でした！');
    }
}
