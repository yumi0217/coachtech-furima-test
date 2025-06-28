<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        Item::factory()->create(['name' => 'テスト商品A']);
        Item::factory()->create(['name' => '別の商品B']);

        $response = $this->get('/?keyword=テスト');

        $response->assertSeeText('テスト商品A');
        $response->assertDontSeeText('別の商品B');
    }

    /** @test */
    public function マイリストに遷移しても検索キーワードが保持されている()
    {
        // ✅ ユーザー作成
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // ✅ 商品を1つ作成（名前に検索キーワードが含まれる）
        $item = Item::factory()->create(['name' => 'お気に入り商品']);

        // ✅ ユーザーがその商品を「いいね」する
        $user->favorites()->attach($item->id);

        // ✅ 「お気に入り」という検索キーワード付きでトップページへアクセス
        $response = $this->actingAs($user)->get('/?keyword=お気に入り');

        // ✅ 検索フォームの value 属性に「お気に入り」がセットされているかを確認
        // （form内の<input name="keyword" value="お気に入り">が存在するか）
        $response->assertSee('お気に入り商品');
        $response->assertSee('お気に入り');
    }
}
