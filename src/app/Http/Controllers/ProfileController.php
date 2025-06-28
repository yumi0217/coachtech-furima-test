<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        $userId = Auth::id();
        $user = \App\Models\User::with(['profile', 'purchases.item', 'items'])->findOrFail($userId);
        $profile = $user->profile ?? $user->profile()->create();
        $purchases = $user->purchases;

        return view('profiles.show', compact('user', 'profile', 'purchases'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $user = Auth::user();



        $profileValidated = $profileRequest->validated();
        $addressValidated = $addressRequest->validated();

        // プロフィール画像のアップロード処理
        if ($profileRequest->hasFile('profile_image')) {
            $imagePath = $profileRequest->file('profile_image')->store('profile_images', 'public');
            $profileValidated['profile_image'] = basename($imagePath);
        }

        // プロフィール情報更新
        $profile = $user->profile ?? $user->profile()->create();

        $profile->fill([
            'username' => $profileValidated['username'], // ← ProfileRequestから取得
            'postal_code' => $addressValidated['postal_code'],
            'address' => $addressValidated['address'],
            'building' => $addressValidated['building'],
            'profile_image' => $profileValidated['profile_image'] ?? $profile->profile_image,
        ]);

        $profile->save();

        // addresses テーブルも更新（別テーブル）
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $addressValidated['postal_code'],
                'address' => $addressValidated['address'],
                'building' => $addressValidated['building'],
            ]
        );

        return redirect('/')->with('status', 'プロフィールを更新しました。');
    }
}
