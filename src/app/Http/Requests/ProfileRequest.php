<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'], // ハイフンありの8文字
            'address' => ['required', 'string'],
            'building' => ['required', 'string'],
            'profile_image' => ['nullable', 'file', 'mimes:jpeg,png'], // .jpeg or .png 拡張子
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'ユーザー名を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフン付きの8文字で入力してください（例: 123-4567）。',
            'address.required' => '住所を入力してください。',
            'building.required' => '建物名を入力してください。',
            'profile_image.mimes' => 'プロフィール画像は.jpegまたは.png形式にしてください。',
            'profile_image.file' => '有効なファイルを選択してください。',
        ];
    }
}
