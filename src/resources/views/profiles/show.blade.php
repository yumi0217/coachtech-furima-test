@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-image">
            @if (!empty($user->profile_image))
            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="プロフィール画像">
            @else
            <div class="avatar-placeholder"></div>
            @endif



        </div>


        <div class="profile-info">
            <div class="user-name">
                <h2>{{ isset($profile) && $profile->username ? $profile->username : $user->name }}</h2>

            </div>
            <div class="edit-button-wrapper">
                <a href="{{ route('profile.edit') }}" class="edit-button">プロフィールを編集</a>
            </div>
        </div>
    </div>

    <div class="tab-menu">
        <span class="tab" data-tab="selling">出品した商品</span>
        <span class="tab active" data-tab="purchased">購入した商品</span>
    </div>

    <div class="tab-underline"></div>

    <div class="tab-content">
        <div id="selling" class="tab-panel"> {{-- 出品した商品 --}}
            <div class="item-list">
                @foreach ($user->items as $item)
                <div class="item-card">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
                        @if ($item->is_sold)
                        <span class="sold-label">SOLD</span>
                        @endif
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </div>
                @endforeach
            </div>
        </div>


        {{-- 購入した商品 --}}
        <div id="purchased" class="tab-panel active"> {{-- ←強制的に表示 --}}
            <div class="item-list">
                <p>購入件数: {{ $purchases->count() }}</p>
                @foreach ($purchases as $purchase)
                @if ($purchase->item)
                <div class="item-card">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $purchase->item->image_url) }}" alt="商品画像">
                    </div>
                    <div class="item-name">{{ $purchase->item->name }}</div>
                </div>
                @endif
                @endforeach

            </div>
        </div>



    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');
        const panels = document.querySelectorAll('.tab-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                panels.forEach(p => p.classList.remove('active'));
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });
    });
</script>
@endsection