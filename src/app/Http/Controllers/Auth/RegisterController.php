<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // ← 追加
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * 会員登録フォーム表示
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * 登録処理
     */
    public function register(RegisterRequest $request)
    {
        // バリデーションは自動的に RegisterRequest が実行します

        // ユーザー作成
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ✅ 登録イベントを発火させて、認証メールを送信させる
        event(new Registered($user));

        // ログインさせる
        Auth::login($user);


        // メール認証画面へリダイレクト
        return redirect()->route('verification.notice');
    }
}
