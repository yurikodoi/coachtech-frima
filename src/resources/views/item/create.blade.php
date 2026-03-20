@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/item-create.css') }}">
@endsection
@section('content')
<main class="item-create-wrapper">
    <div class="item-create-container">
        <h1 class="item-create-title">商品の出品</h1>
        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" class="item-form" novalidate>
            @csrf
            <section class="item-form-section">
                <p class="item-form-label">商品画像</p>
                <div class="item-image-upload">
                    <label class="item-image-button">画像を選択する
                        <input type="file" name="item_image" class="item-image-input">
                    </label>
                    @error('item_image')
                        <p style="color: red; font-size: 0.8rem;">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <section class="item-form-section">
                <h1 class="item-form-sub-title">商品の詳細</h1>
                <div class="item-form-group">
                    <label class="item-form-label">カテゴリー</label>
                    <div class="item-category-list">
                        @foreach($categories as $category)
                        <div class="item-category-item">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" id="category-{{ $category->id }}" class="item-category-input" {{ (is_array(old('category_id')) && in_array($category->id, old('category_id'))) ? 'checked' : '' }} style="display: none;">
                            <label for="category-{{ $category->id }}" class="item-category-label">{{ $category->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('category_id')
                        <p style="color: red; font-size: 0.8rem;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="item-form-group">
                    <label class="item-form-label">商品の状態</label>
                    <select name="condition" class="item-form-select">
                        <option value="">選択してください</option>
                        <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                        <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                    @error('condition')
                        <p style="color: red; font-size: 0.8rem;">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <section class="item-form-section">
                <h1 class="item-form-sub-title">商品名と説明</h1>
                <div class="item-form-group">
                    <label class="item-form-label">商品名</label>
                    <input type="text" name="name" class="item-form-input" value="{{ old('name') }}">
                    @error('name')
                        <p style="color: red; font-size: 0.8rem;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="item-form-group">
                    <label class="item-form-label">ブランド名</label>
                    <input type="text" name="brand" class="item-form-input" value="{{ old('brand') }}">
                </div>
                <div class="item-form-group">
                    <label class="item-form-label">商品の説明</label>
                    <textarea name="description" rows="5" class="item-form-textarea">{{ old('description') }}</textarea>
                    @error('description')
                        <p style="color: red; font-size: 0.8rem;">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <section class="item-form-section">
                <div class="item-form-group">
                    <label class="item-form-label">販売価格</label>
                    <div class="item-price-input-wrapper">
                        <span class="item-price-unit">¥</span>
                        <input type="number" name="price" class="item-form-input" value="{{ old('price') }}">
                    </div>
                    @error('price')
                        <p style="color: red; font-size: 0.8rem;">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <div class="item-form-button-container">
                <button type="submit" class="item-submit-button">出品する</button>
            </div>
        </form>
    </div>
</main>
@endsection