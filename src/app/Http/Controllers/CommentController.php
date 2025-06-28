<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $itemId)
    {
        \App\Models\Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
            'content' => $request->input('content'),
        ]);

        return redirect()->route('items.show', ['id' => $itemId])->with('success', 'コメントを投稿しました');
    }
}
