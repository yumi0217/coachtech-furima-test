<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレスが未入力のときバリデーションエラー()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_パスワードが未入力のときバリデーションエラー()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_入力情報が誤っているときエラーメッセージ()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('email'); // カスタムメッセージがあればメッセージでもOK
    }

    public function test_正しい情報ならログイン成功()
    {
        $user = User::factory()->create([
            'email' => 'login@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/'); // ログイン後の遷移先に応じて変更
        $this->assertAuthenticatedAs($user);
    }
}
