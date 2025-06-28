@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/addresses/edit.css') }}">

<div class="address-edit-container">
    <h2>住所の変更</h2>

    <form method="POST" action="{{ route('addresses.update') }}">
        @csrf
        <input type="hidden" name="item_id" value="{{ $itemId }}">


        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}">
            @error('postal_code')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" value="{{ old('address', $address->address ?? '') }}">
            @error('address')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $address->building ?? '') }}">
            @error('building')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-button">
            <button type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection