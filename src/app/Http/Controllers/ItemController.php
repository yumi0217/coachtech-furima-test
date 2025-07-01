<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $type = $request->input('type', 'recommend'); // ← デフォルトは「おすすめ」

        $query = Item::query();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('brand_name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        $items = $query->get();

        $mylist = collect();
        if (auth()->check()) {
            $mylistQuery = auth()->user()->favorites()
                ->where('items.user_id', '!=', auth()->id());

            if (!empty($keyword)) {
                $mylistQuery->where(function ($q) use ($keyword) {
                    $q->where('items.name', 'like', "%{$keyword}%")
                        ->orWhere('items.brand_name', 'like', "%{$keyword}%")
                        ->orWhere('items.description', 'like', "%{$keyword}%");
                });
            }

            $mylist = $mylistQuery->get();
        }

        return view('items.index', compact('items', 'mylist', 'keyword', 'type'));
    }







    public function show($id)
    {
        $item = Item::with(['categories', 'favoritedBy'])->findOrFail($id);

        // コメントをユーザー付きで降順に取得
        $comments = $item->comments()->with('user')->latest()->get();

        // ログイン中ユーザーがこの商品をお気に入りしているか判定
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()->hasFavorited($item);
        }

        return view('items.show', compact('item', 'comments', 'isFavorited'));
    }




    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();


        // 画像をstorageに保存
        $path = $request->file('image')->store('items', 'public');

        $item = new Item();
        $item->user_id = auth()->id(); // ログインユーザーが出品者
        $item->name = $request->name;
        $item->brand_name = $request->brand_name ?? null;
        $item->price = $request->price;
        $item->description = $request->description;
        $item->condition = $request->condition;
        $item->image_url = $path; // 🔥 Bladeと一致させるためimage_urlに変更
        $item->is_sold = false;
        $item->save();

        // ★ カテゴリー保存（中間テーブル） ←任意（存在する場合）
        if ($request->has('categories')) {
            $categoryIds = \App\Models\Category::whereIn('name', $request->categories)->pluck('id');
            $item->categories()->sync($categoryIds);
        }

        return redirect()->route('items.index')->with('success', '商品を出品しました');
    }


    public function create()
    {
        return view('items.create');
    }

    public function testMylist()
    {
        $items = collect(); // おすすめ商品は空にする
        $type = 'mylist';

        $mylist = collect();
        if (auth()->check()) {
            $mylist = auth()->user()
                ->favorites()
                ->where('items.user_id', '!=', auth()->id()) // ← 自分の商品を除外
                ->get();
        }

        return view('items.index', compact('items', 'mylist', 'type'));
    }
}
