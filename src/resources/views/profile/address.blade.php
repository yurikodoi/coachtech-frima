@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection
@section('content')
<div class="address-page">
    <h1 class="address-page__title">住所の変更</h1>
    <form action="{{ route('address.update', ['itemId' => $itemId]) }}" method="POST" class="address-page__form">
        @csrf
        <div class="form-group">
            <label class="form-group__label" for="postcode">郵便番号</label>
            <input type="text" name="postcode" id="postcode" class="form-group__input" value="{{ old('postcode', $user->postcode) }}">
            @error('postcode')
                <span class="form-group__error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__label" for="address">住所</label>
            <input type="text" name="address" id="address" class="form-group__input" value="{{ old('address', $user->address) }}">
            @error('address')
                <span class="form-group__error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__label" for="building">建物名</label>
            <input type="text" name="building" id="building" class="form-group__input" value="{{ old('building', $user->building) }}">
        </div>
        <button type="submit" class="address-page__submit-btn">更新する</button>
    </form>
</div>
@endsection