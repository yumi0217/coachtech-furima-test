<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{


    public function create(Request $request)
    {
        $itemId = $request->query('item_id');
        $item = Item::findOrFail($itemId);

        $address = (object)[
            'postal_code' => '100-0001',
            'full_address' => '東京都千代田区千代田1-1',
        ];

        if (auth()->check() && auth()->user()->address) {
            $userAddress = auth()->user()->address;
            $address = (object)[
                'postal_code' => $userAddress->postal_code,
                'full_address' => $userAddress->address . ' ' . ($userAddress->building ?? ''),
            ];
        }

        return view('purchases.create', compact('item', 'address'));
    }

    public function store(PurchaseRequest $request)
    {
        $validated = $request->validated();
        $item = Item::findOrFail($validated['item_id']);

        $purchase = new Purchase();
        $purchase->user_id = auth()->id();
        $purchase->item_id = $item->id;
        $purchase->save();

        $item->is_sold = true;
        $item->save();

        return redirect()->route('purchase.success');
    }

    public function redirectToCheckout($method, Item $item)
    {
        if (!$item->is_sold) {
            $item->is_sold = true;
            $item->save();

            if (auth()->check()) {
                auth()->user()->purchases()->create([
                    'item_id' => $item->id,
                    'purchased_at' => now(),
                ]);
            }
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index'),
            'cancel_url' => route('items.show', $item->id),
        ]);

        return redirect($session->url);
    }

    public function cancel()
    {
        return view('purchase.cancel');
    }
}
