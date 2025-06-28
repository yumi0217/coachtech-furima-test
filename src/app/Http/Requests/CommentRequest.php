<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認証済みユーザーのみ許可する場合は true
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:255', // 入力必須・最大255文字
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'コメントを入力してください。',
            'content.max' => 'コメントは255文字以内で入力してください。',
        ];
    }
}
