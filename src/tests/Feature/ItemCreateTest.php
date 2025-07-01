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
        Storage::fake('public');

        $category = Category::create(['name' => 'ファッション']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // ダミー画像（拡張子・MIMEタイプ付き）
        $image = UploadedFile::fake()->create('dummy.jpg', 100, 'image/jpeg');

        $formData = [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => 'これはテスト商品です',
            'condition' => '良好',
            'categories' => ['ファッション'],
            'image' => $image,
        ];

        $response = $this->actingAs($user)->post(route('items.store'), $formData);

        // DBに保存されたか
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => 'これはテスト商品です',
            'condition' => '良好',
            'user_id' => $user->id,
        ]);

        // 画像が保存されたか
        Storage::disk('public')->assertExists('items/' . $image->hashName());

        // 中間テーブル確認
        $this->assertDatabaseCount('item_categories', 1);

        $response->assertRedirect(route('items.index'));
    }
}
