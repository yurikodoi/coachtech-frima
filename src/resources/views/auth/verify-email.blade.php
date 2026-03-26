@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <div class="verify-email__container">

        <div class="verify-email__message">
            <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p>メール認証を完了してください。</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="verify-email__alert">
                新しい認証リンクを登録したメールアドレスに送信しました。
            </div>
        @endif

        <div class="verify-email__action">
            <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="verify-email__resend-link">
                    認証メールを再送する
                </button>
            </form>
        </div>
    </div>
</div>
@endsection