@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">

<div class="item-detail-container">
    <div class="item-left">
        <div class="item-image">
            <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
        </div>
    </div>

    <div class="item-right">
        <div class="item-title-group">
            <h1 class="item-title">{{ $item->name }}</h1>
            @if ($item->brand_name)
            <p class="item-brand-name">{{ $item->brand_name }}</p>
            @endif
        </div>

        <p class="item-price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

        <div class="item-actions">
            <div class="icons-wrapper">
                <div class="icons-row">
                    <div class="icon-group favorite">
                        <form method="POST" action="{{ route('favorites.toggle', ['item' => $item->id]) }}">
                            @csrf
                            <button type="submit" class="favorite-button" style="all: unset; cursor: pointer;">
                                <img src="{{ asset($isFavorited ? 'images/星のアイコン_黄色.png' : 'images/星のアイコン.png') }}" alt="お気に入り" class="icon">
                            </button>
                        </form>
                        <span class="icon-number">{{ $item->favoritedBy->count() }}</span>
                    </div>


                    <div class="icon-group">
                        <img src="{{ asset('images/吹き出しアイコン.png') }}" alt="コメント" class="icon">
                        <span class="icon-number">{{ $comments->count() }}</span>

                    </div>
                </div>

                <a href="{{ route('purchases.create', ['item_id' => $item->id]) }}" class="buy-button">購入手続きへ</a>

            </div>
        </div>


        <div class="item-description">
            <h2>商品説明</h2>
            <p><strong>カラー：</strong>{{ $item->color ?? '記載なし' }}</p>
            <p>{{ $item->is_new ? '新品' : '中古' }}</p>
            <p>商品の状態は{{ $item->condition }}です。</p>
            <p>購入後、即発送いたします。</p>
        </div>


        <div class="item-info">
            <h2>商品の情報</h2>
            <p><strong>カテゴリー：</strong>
                @foreach($item->categories as $category)
                <span class="category-tag">{{ $category->name }}</span>
                @endforeach
            </p>
            <p><strong>商品の状態：</strong> {{ $item->is_new ? '新品' : '中古' }}</p>
            <p><strong>詳細：</strong> {{ $item->condition }}</p>
        </div>

        <div class="comments-section">
            <div
                class="comments-toggle {{ $comments->count() > 2 ? '' : 'disabled' }}"
                data-count="{{ $comments->count() }}"
                style="cursor: pointer;">
                コメント(<span id="comment-count">{{ $comments->count() }}</span>)
            </div>




            @if($comments->isEmpty())
            <p>まだコメントがありません。</p>
            @else
            {{-- 最初の2件だけ表示 --}}
            @foreach ($comments->take(2) as $comment)
            <div class="comment">
                @if ($comment->user->profile_image ?? false)
                <img src="{{ asset('storage/profile_images/' . $comment->user->profile_image) }}" alt="avatar" class="comment-avatar">
                @else
                <div class="comment-avatar avatar-placeholder"></div>
                @endif

                <div class="comment-body">
                    <strong>{{ $comment->user->name ?? '匿名ユーザー' }}</strong>
                    <p>{{ $comment->content }}</p>
                </div>
            </div>
            @endforeach

            {{-- 3件目以降は非表示に --}}
            {{-- 3件目以降は非表示に（常に出力） --}}
            <div class="extra-comments hidden">
                @foreach ($comments->slice(2) as $comment)
                <div class="comment">
                    @if ($comment->user->profile_image ?? false)
                    <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="avatar" class="comment-avatar">
                    @else
                    <div class="comment-avatar avatar-placeholder"></div>
                    @endif
                    <div class="comment-body">
                        <strong>{{ $comment->user->name ?? '匿名ユーザー' }}</strong>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            @endif

            {{-- コメント投稿フォーム --}}
            <form action="{{ route('comments.store', ['item' => $item->id]) }}" method="POST" class="comment-form">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <label for="comment">商品へのコメント</label>
                <textarea name="content" rows="4" class="{{ $errors->has('content') ? 'is-invalid' : '' }}">{{ old('content') }}</textarea>
                @if ($errors->has('content'))
                <p class="error-message">{{ $errors->first('content') }}</p>
                @endif
                <button type="submit" class="comment-button">コメントを送信する</button>
            </form>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.querySelector('.comments-toggle');
        const extraComments = document.querySelector('.extra-comments');

        if (toggle && extraComments && !toggle.classList.contains('disabled')) {
            const commentCount = toggle.dataset.count;

            toggle.addEventListener('click', function() {
                extraComments.classList.toggle('hidden');
                toggle.innerHTML = extraComments.classList.contains('hidden') ?
                    `コメント(<span id="comment-count">${commentCount}</span>)` :
                    `コメントをすべて表示中（<span id="comment-count">${commentCount}</span>）`;
            });
        }
    });
</script>

@endpush