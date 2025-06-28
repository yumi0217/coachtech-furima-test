@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="purchase-container">
    <div class="left-column">
        <div class="item-section">
            <div class="item-image">
                <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
            </div>
            <div class="item-details">
                <h2>{{ $item->name }}</h2>
                <p class="price">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>

        {{-- 支払い方法と配送先 --}}
        <form id="purchase-form">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="payment_method" id="selected_payment_method" value="">

            <div class="payment-method">
                <label>支払い方法</label>
                <div class="custom-select-wrapper">
                    <div class="custom-select-trigger">選択してください</div>
                    <div class="custom-options">
                        <div class="custom-option" data-value="konbini">コンビニ支払い</div>
                        <div class="custom-option" data-value="card">カード支払い</div>
                    </div>
                    <input type="hidden" name="payment_method" id="selected_payment_method" required>
                    @error('payment_method')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>





            <div class="shipping-address">
                <div class="shipping-header">
                    <label>配送先</label>
                    <a href="{{ route('addresses.edit', ['item_id' => $item->id]) }}">変更する</a>
                </div>
                <p>〒 {{ $address->postal_code ?? 'XXX-YYYY' }}</p>
                <p>{{ $address->full_address ?? 'ここには住所と建物が入ります' }}</p>
                <input type="hidden" name="address_id" value="{{ $address->id ?? '' }}">
                @error('address_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>

    <div class="right-column">
        <div class="purchase-summary">
            <table>
                <tr class="with-border">
                    <th>商品代金</th>
                    <td>¥ {{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="summary-payment">未選択</td>
                </tr>
            </table>
        </div>

        {{-- Stripe にリダイレクトする form（JSで送信） --}}
        <form id="redirect-form" method="POST" style="display: none;">
            @csrf
        </form>

        <button type="button" class="purchase-button" id="purchase-button">購入する</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.querySelector('.custom-select-trigger');
        const options = document.querySelector('.custom-options');
        const optionItems = document.querySelectorAll('.custom-option');
        const hiddenInput = document.getElementById('selected_payment_method');
        const summaryPayment = document.getElementById('summary-payment');
        const purchaseButton = document.getElementById('purchase-button');
        const itemId = document.querySelector('input[name="item_id"]').value;

        // トリガークリックで開閉
        trigger.addEventListener('click', () => {
            options.style.display = options.style.display === 'block' ? 'none' : 'block';
        });

        // 選択肢クリック時の動作
        optionItems.forEach(option => {
            option.addEventListener('click', () => {
                trigger.textContent = option.textContent;
                hiddenInput.value = option.dataset.value;
                summaryPayment.textContent = option.textContent;
                options.style.display = 'none';
            });
        });

        // 外クリックで閉じる
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.custom-select-wrapper')) {
                options.style.display = 'none';
            }
        });

        // 購入ボタン押下時の処理
        purchaseButton.addEventListener('click', function() {
            if (!hiddenInput.value) {
                alert('支払い方法を選択してください');
                return;
            }

            const form = document.getElementById('redirect-form');
            form.action = `/purchase/checkout/${hiddenInput.value}/${itemId}`;
            form.method = 'POST';
            form.submit();
        });
    });
</script>

@endsection