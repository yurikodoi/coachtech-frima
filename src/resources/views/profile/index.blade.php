@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="mypage__header">
        <div class="mypage__user-info">
            <div class="mypage__image">
                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('img/default-user.png') }}" alt="ユーザーアイコン">
            </div>
            <h2 class="mypage__username">{{ $user->name }}</h2>
            <a href="{{ route('profile.edit') }}" class="mypage__edit-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage__tabs">
        <input type="radio" name="tab_item" id="tab_selling" {{ $page === 'sell' ? 'checked' : '' }} onclick="location.href='/mypage?page=sell'">
        <label class="mypage__tab-label" for="tab_selling">出品した商品</label>

        <input type="radio" name="tab_item" id="tab_bought" {{ $page === 'buy' ? 'checked' : '' }} onclick="location.href='/mypage?page=buy'">
        <label class="mypage__tab-label" for="tab_bought">購入した商品</label>

        <div class="mypage__tab-content" id="selling_content">
            <div class="item-grid">
                @foreach($items as $item)
                <div class="item-card">
                    <div class="item-image-container">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}" class="item-img">
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mypage__tab-content" id="bought_content">
            <div class="item-grid">
                @foreach($orders as $order)
                    <div class="item-card">
                        <div class="item-image-container">
                            <img src="{{ asset($order->item->image_url) }}" alt="{{ $order->item->name }}" class="item-img">
                            @if($order->item->is_sold)
                            <div class="label-sold">Sold</div>
                            @endif
                        </div>
                        <p class="item-name">{{ $order->item->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection