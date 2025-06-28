<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出品商品情報が正しく保存される()
    {
        Storage::fake('public'); // 画像の保存先をモック

        // カテゴリ作成（任意で複数）
        $category = Category::create(['name' => 'ファッション']);

        // ログインユーザー作成
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // ダミー画像
        $image = UploadedFile::fake()->image('sample.jpg');

        // 入力データ
        $formData = [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => 'これはテスト商品です',
            'condition' => '良好',
            'categories' => ['ファッション'],
            'image' => $image,
        ];

        // 出品処理を実行
        $response = $this->actingAs($user)->post(route('items.store'), $formData);

        // 保存されたか確認
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => 'これはテスト商品です',
            'condition' => '良好',
            'user_id' => $user->id,
        ]);

        // 画像が保存されているか確認
        Storage::disk('public')->assertExists('items/' . $image->hashName());

        // カテゴリーも中間テーブルに登録されているか
        $this->assertDatabaseCount('item_categories', 1);


        // リダイレクト先確認
        $response->assertRedirect(route('items.index'));
    }
}
