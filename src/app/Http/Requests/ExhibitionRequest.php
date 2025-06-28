<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認証済みユーザーのみなら true
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png'],
            'categories' => ['required', 'array', 'min:1'], // カテゴリーは1つ以上
            'condition' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'categories.*' => 'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'description.max' => '商品の説明は255文字以内で入力してください。',
            'image.required' => '商品画像をアップロードしてください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください。',
            'categories.required' => 'カテゴリーを選択してください。',
            'categories.min' => 'カテゴリーを1つ以上選択してください。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0円以上にしてください。',
        ];
    }
}
