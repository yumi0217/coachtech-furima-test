<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first() ?? new Address();
        $itemId = $request->query('item_id'); // ← item_idを取得

        return view('addresses.edit', compact('address', 'itemId')); // ← Bladeに渡す
    }







    public function update(AddressRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
                'building' => $validated['building'],
            ]
        );

        // item_id を取得して購入画面へ戻す
        $itemId = $request->input('item_id');
        return redirect()->route('purchases.create', ['item_id' => $itemId])
            ->with('status', '住所を更新しました');
    }
}
