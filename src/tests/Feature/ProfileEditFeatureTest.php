<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileEditFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール編集画面に初期値が正しく表示される()
    {
        // ✅ ここからがその該当部分
        Storage::fake('public');

        // $image = UploadedFile::fake()->image('test.png');
        // $image->storeAs('profile_images', 'test.png', 'public');



        // ユーザー作成
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // プロフィール作成時に画像パスを使う
        $profile = Profile::create([
            'user_id' => $user->id,
            'username' => 'テスト太郎',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル',
            'profile_image' => 'profile_images/test.png',
            'nickname' => 'たろう',
            'birthday' => '2000-01-01',
            'gender' => 'male',
        ]);

        // ログイン状態で編集画面にアクセス
        $response = $this->actingAs($user)->get(route('profile.edit'));

        // 各項目がHTMLに出力されているか検証
        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区1-1-1');
        $response->assertSee('テストビル');
        $response->assertSee('sample-profile.png');
    }
}
