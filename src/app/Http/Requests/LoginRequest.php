<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認証なしで使うので true のままでOK
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレス形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
    }

    protected function passedValidation()
    {
        // ログイン認証チェック
        if (!Auth::attempt($this->only('email', 'password'))) {
            $this->validator->errors()->add('email', 'ログイン情報が登録されていません。');
        }
    }
}
