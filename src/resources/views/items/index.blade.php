@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">

<div class="page-container">

    <!-- タブセクション -->
    <div class="tab-section">
        <div class="tab-menu">
            <a href="{{ route('items.index', ['type' => 'recommend', 'keyword' => request('keyword')]) }}"
                class="tab {{ $type === 'recommend' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('items.index', ['type' => 'mylist', 'keyword' => request('keyword')]) }}"
                class="tab {{ $type === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <div class="tab-underline"></div>
    </div>

    <!-- 商品一覧 -->
    <div class="item-list-wrapper">
        @if ($type === 'recommend')
        <div class="item-list recommend-list">
            @foreach($items as $item)
            <a href="{{ route('items.show', $item->id) }}" class="item-card">
                <div class="item-image-wrapper">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                        @if($item->is_sold)
                        <span class="sold-label">SOLD</span>
                        @endif
                    </div>
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </a>
            @endforeach
        </div>
        @elseif ($type === 'mylist')
        <div class="item-list mylist-list">
            @forelse($mylist as $item)
            <a href="{{ route('items.show', $item->id) }}" class="item-card">
                <div class="item-image-wrapper">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                        @if($item->is_sold)
                        <span class="sold-label">SOLD</span>
                        @endif
                    </div>
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </a>
            @empty
            <p style="padding-left: 69px; font-size: 18px;">まだ商品がありません。</p>
            @endforelse
        </div>
        @endif
    </div>

</div>
@endsection