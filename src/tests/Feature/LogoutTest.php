<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログアウトできること()
    {
        // ユーザー作成（テスト用）
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // ログアウト処理を実行
        $response = $this->post('/logout');

        // ログインページへリダイレクトされることを確認
        $response->assertRedirect('/login');

        // 認証されていない（ログアウト済）ことを確認
        $this->assertGuest();
    }
}
