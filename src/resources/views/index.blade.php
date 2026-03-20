@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')
<div class="item-index">
    <nav class="tabs">
        <a href="{{ url('/?tab=index' . (request('keyword') ? '&keyword='.request('keyword') : '')) }}" 
           class="tab {{ $selectedTab === 'index' ? 'tab-active' : '' }}">おすすめ</a>
        <a href="{{ url('/?tab=mylist' . (request('keyword') ? '&keyword='.request('keyword') : '')) }}" 
           class="tab {{ $selectedTab === 'mylist' ? 'tab-active' : '' }}">マイリスト</a>
    </nav>
    <hr class="separator">
    <div class="item-grid">
        @foreach($items as $item)
            <article class="item-card">
                <a href="{{ url('/item/' . $item->id) }}" class="item-link">
                    <div class="item-image-container">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                        @if(isset($item->is_sold) && $item->is_sold)
                            <div class="label-sold">Sold</div>
                        @endif
                    </div>
                    <div class="item-info">
                        <p class="item-name">{{ $item->name }}</p>
                    </div>
                </a>
            </article>
        @endforeach
    </div>
</div>
@endsection