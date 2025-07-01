<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('comments.store', ['item' => $item->id]), [
                'content' => 'テストコメント',
            ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    /** @test */
    /** @test */
    /** @test */
    public function ログインしていないユーザーはコメントを送信できない()
    {
        $item = \App\Models\Item::factory()->create();

        try {
            $this->post(route('comments.store', ['item' => $item->id]), [
                'content' => '未ログインコメント',
            ]);
        } catch (\Throwable $e) {
            // エラーが出てもスルーして下の確認だけ行う
        }

        $this->assertDatabaseMissing('comments', [
            'content' => '未ログインコメント',
        ]);
    }



    /** @test */
    public function コメントが未入力の場合バリデーションエラーになる()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('comments.store', ['item' => $item->id]), [
                'content' => '',
            ]);

        $response->assertSessionHasErrors('content');
    }

    /** @test */
    /** @test */
    /** @test */
    /** @test */
    public function コメントは256文字以上の場合は保存されない()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post(route('comments.store', ['item' => $item->id]), [
            'content' => $longComment,
        ]);

        // バリデーションエラーを確認
        $response->assertSessionHasErrors('content');

        // DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'content' => $longComment,
        ]);
    }
}
