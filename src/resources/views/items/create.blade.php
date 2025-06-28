@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="create-container">
    <h1 class="page-title">商品の出品</h1>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="item-form">
        @csrf

        <!-- 商品画像 -->
        <div class="form-section">
            <label class="section-label">商品画像</label>
            <div class="image-upload-area">
                <label for="image-upload" class="image-upload-label">画像を選択する</label>
                <img id="preview-image" class="preview-image" src="#" alt="プレビュー画像" style="display: none;" />
                <input id="image-upload" type="file" name="image" accept="image/*" hidden>
            </div>
            @error('image')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>



        <!-- 商品の詳細 見出し -->
        <div class="section-heading-wrapper">
            <h2 class="section-heading">商品詳細</h2>
        </div>


        <!-- カテゴリ選択 -->
        <div class="form-group">
            <label class="category-label">カテゴリー</label>
            <div class="checkbox-group">
                <button type="button" class="category-button" data-value="ファッション">ファッション</button>
                <button type="button" class="category-button" data-value="家電">家電</button>
                <button type="button" class="category-button" data-value="インテリア">インテリア</button>
                <button type="button" class="category-button" data-value="レディース">レディース</button>
                <button type="button" class="category-button" data-value="メンズ">メンズ</button>
                <button type="button" class="category-button" data-value="メンズ">コスメ</button>
                <button type="button" class="category-button" data-value="メンズ">本</button>
                <button type="button" class="category-button" data-value="メンズ">ゲーム</button>
                <button type="button" class="category-button" data-value="メンズ">スポーツ</button>
                <button type="button" class="category-button" data-value="メンズ">キッチン</button>
                <button type="button" class="category-button" data-value="メンズ">ハンドメイド</button>
                <button type="button" class="category-button" data-value="メンズ">アクセサリー</button>
                <button type="button" class="category-button" data-value="メンズ">おもちゃ</button>
                <button type="button" class="category-button" data-value="メンズ">ベビー・キッズ</button>
            </div>

            {{-- 選択結果はここに渡す --}}
            <div id="category-values"></div> {{-- ここに複数 hidden input を動的に追加 --}}
            @if ($errors->has('categories') || $errors->has('categories.0'))
            <div class="text-danger">
                {{ $errors->first('categories') ?? $errors->first('categories.0') }}
            </div>
            @endif

        </div>


        <!-- 商品の状態 -->
        <div class="form-section">
            <label class="section-label">商品の状態</label>
            <div class="custom-select-wrapper">
                <div class="custom-select-trigger">選択してください</div>
                <div class="custom-options">
                    <div class="custom-option" data-value="良好">良好</div>
                    <div class="custom-option" data-value="目立った傷や汚れなし">目立った傷や汚れなし</div>
                    <div class="custom-option" data-value="やや傷や汚れあり">やや傷や汚れあり</div>
                    <div class="custom-option" data-value="状態が悪い">状態が悪い</div>
                </div>
                <input type="hidden" name="condition">
                @error('condition')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>
        </div>


        <!-- 商品名・説明など -->
        <div class="form-section">
            <label class="section-label">商品名</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-section">
            <label class="section-label">ブランド名</label>
            <input type="text" name="brand_name" value="{{ old('brand_name') }}">
        </div>



        <div class="form-section">
            <label class="section-label">商品の説明</label>
            <textarea name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 販売価格 -->
        <div class="form-group">
            <label>販売価格</label>
            <div class="price-input">
                <span>¥</span>
                <input type="number" name="price" value="{{ old('price') }}">
            </div>
            @error('price')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // カテゴリーボタン（既存）
        const buttons = document.querySelectorAll('.category-button');
        const categoryContainer = document.getElementById('category-values');
        let selectedValues = [];

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const value = this.dataset.value;

                if (selectedValues.includes(value)) {
                    selectedValues = selectedValues.filter(v => v !== value);
                    this.classList.remove('selected');
                } else {
                    selectedValues.push(value);
                    this.classList.add('selected');
                }

                // hidden input を動的に再生成
                categoryContainer.innerHTML = ''; // 一旦クリア
                selectedValues.forEach(val => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'categories[]';
                    input.value = val;
                    categoryContainer.appendChild(input);
                });
            });
        });


        // ▼ 商品の状態カスタムセレクト ▼
        const trigger = document.querySelector('.custom-select-trigger');
        const options = document.querySelector('.custom-options');
        const optionItems = document.querySelectorAll('.custom-option');
        const hiddenInput = document.querySelector('input[name="condition"]');

        const imageInput = document.getElementById('image-upload');
        const previewImage = document.getElementById('preview-image');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const imageUrl = URL.createObjectURL(file);
                previewImage.src = imageUrl;
                previewImage.style.display = 'block';

                // ✅ 画像を選択したらボタンを非表示に
                const uploadLabel = document.querySelector('.image-upload-label');
                uploadLabel.style.display = 'none';
            }
        });


        // トリガークリック → 表示切替
        trigger.addEventListener('click', () => {
            options.style.display = options.style.display === 'block' ? 'none' : 'block';
        });

        // 選択肢クリック → 値反映＋閉じる
        optionItems.forEach(option => {
            option.addEventListener('click', () => {
                trigger.textContent = option.textContent;
                hiddenInput.value = option.dataset.value;
                options.style.display = 'none';
            });
        });

        // 外クリックで閉じる
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.custom-select-wrapper')) {
                options.style.display = 'none';
            }
        });
    });
</script>
@endsection