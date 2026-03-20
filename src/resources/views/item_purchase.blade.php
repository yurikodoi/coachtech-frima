@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection
@section('content')
<div class="purchase-page">
    <main class="purchase-page__main">
        <section class="item-summary">
            <div class="item-summary__image-wrapper">
                <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
            </div>
            <div class="item-summary__details">
                <h2 class="item-summary__name">{{ $item->name }}</h2>
                <p class="item-summary__price">¥ {{ number_format($item->price) }}</p>
            </div>
        </section>
        <section class="payment-method">
            <h3 class="purchase-page__sub-title">支払い方法</h3>
            <select name="paymentMethod" class="payment-method__select" onchange="updatePaymentMethod(this)">
                <option value="" disabled selected>選択してください</option>
                <option value="konbini">コンビニ支払い</option>
                <option value="card">カード支払い</option>
            </select>
        </section>
        <section class="shipping-address">
            <div class="shipping-address__header">
                <h3 class="purchase-page__sub-title">配送先</h3>
                <a href="{{ route('address.edit', ['itemId' => $item->id]) }}" class="shipping-address__edit-link">変更する</a>
            </div>
            @php
                $newAddress = session('new_shipping');
            @endphp
            <div class="shipping-address__info">
                <p class="shipping-address__text shipping-address__text--postcode">
                    〒 {{ $newAddress['postcode'] ?? $user->postcode }}
                </p>
                <p class="shipping-address__text">
                    {{ $newAddress['address'] ?? $user->address }} 
                    {{ $newAddress['building'] ?? $user->building }}
                </p>
            </div>
        </section>
    </main>
    <aside class="purchase-page__sidebar">
        <div class="checkout-box">
            <table class="checkout-box__table">
                <tr class="checkout-box__row">
                    <th class="checkout-box__header">商品代金</th>
                    <td class="checkout-box__data">¥ {{ number_format($item->price) }}</td>
                </tr>
                <tr class="checkout-box__row">
                    <th class="checkout-box__header">支払い方法</th>
                    <td class="checkout-box__data js-selected-payment">選択してください</td>
                </tr>
            </table>
            <form action="{{ route('purchase.store', $item->id) }}" method="POST" class="checkout-box__form">
                @csrf
                <input type="hidden" name="payment_method" class="js-payment-hidden">
                <button type="submit" class="checkout-box__submit-button">購入する</button>
            </form>
        </div>
    </aside>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkoutForm = document.querySelector('.checkout-box__form');
        checkoutForm.addEventListener('submit', (e) => {
            const paymentMethod = document.querySelector('.js-payment-hidden').value;
            if (!paymentMethod) {
                e.preventDefault();
                alert('支払い方法を選択してください');
            }
        });
    });
    function updatePaymentMethod(selectElement) {
        const selectedText = selectElement.options[selectElement.selectedIndex].text;
        const selectedValue = selectElement.value;
        const displayElement = document.querySelector('.js-selected-payment');
        if (displayElement) {
            displayElement.textContent = selectedText;
        }
        const hiddenInput = document.querySelector('.js-payment-hidden');
        if (hiddenInput) {
            hiddenInput.value = selectedValue;
        }
    }
</script>
@endsection