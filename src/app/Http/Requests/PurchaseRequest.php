<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => ['required', 'in:konbini,card'],
            'address_id' => ['required', 'exists:addresses,id'], // 選択されている必要あり
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '支払い方法が正しくありません。',
            'address_id.required' => '配送先を選択してください。',
            'address_id.exists' => '配送先が正しくありません。',
        ];
    }
}
