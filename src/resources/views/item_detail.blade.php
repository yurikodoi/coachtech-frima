@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection
@section('content')
<div class="item-detail-container">
    <div class="item-main-content">
        <div class="item-image-section">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
            @if(isset($item->is_sold) && $item->is_sold)
                <span class="sold-label" style="position: absolute; top: 10px; left: 10px; background: red; color: white; padding: 5px 10px; font-weight: bold;">Sold</span>
            @endif
        </div>
        <div class="item-info-section">
            <div class="item-header">
                <h1 class="item-name">{{ $item->name }}</h1>
                <p class="brand-name">{{ $item->brand }}</p>
                <p class="item-price">
                    <span class="currency">¥</span>{{ number_format($item->price) }}<span class="tax-label">(税込)</span>
                </p>
                <div class="item-stats">
                    <div class="stat-item">
                        <form action="{{ route('like', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-like">
                                <img src="{{ asset(Auth::check() && $item->is_liked_by_auth_user() ? 'img/icon-heart-red.png' : 'img/icon-heart.png') }}" alt="いいね" class="icon-img">
                            </button>
                        </form>
                        <span class="count">{{ $item->likes->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <img src="{{ asset('img/icon-comment.png') }}" alt="コメント" class="icon-img">
                        <span class="count">{{ $item->comments->count() }}</span>
                    </div>
                </div>
            </div>
            <a href="/purchase/{{ $item->id }}" class="btn-purchase">購入手続きへ</a>
            <div class="description-group">
                <h2 class="section-title">商品説明</h2>
                <p class="description-text">{{ $item->description }}</p>
            </div>
            <div class="info-group">
                <h2 class="section-title">商品の情報</h2>
                <div class="info-row">
                    <span class="info-label">カテゴリー</span>
                    <div class="category-tags">
                        @foreach($item->categories as $category)
                            <span class="tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label">商品の状態</span>
                    <span class="condition-text">{{ $item->condition_text }}</span>
                </div>
            </div>
            <div class="comment-group">
                <h2 class="section-title">コメント({{ $item->comments->count() }})</h2>
                @foreach($item->comments as $comment)
                    <div class="comment-item">
                        <div class="user-avatar-group">
                            <div class="user-avatar">
                                <img src="{{ $comment->user->profile_image_url }}" alt="{{ $comment->user->name }}">
                            </div>
                            <p class="user-name">{{ $comment->user->name }}</p>
                        </div>
                        <div class="comment-body">
                            <div class="comment-text">{{ $comment->comment }}</div>
                        </div>
                    </div>
                @endforeach
                <div class="comment-form">
                    <h3 class="form-title">商品へのコメント</h3>
                    <form action="{{ route('comment.store', $item->id) }}" method="POST">
                        @csrf
                        <textarea name="comment" class="comment-textarea"></textarea>
                        @error('comment')
                            <p class="error-message" style="color: red; font-size: 0.8em; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn-comment">コメントを送信する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection