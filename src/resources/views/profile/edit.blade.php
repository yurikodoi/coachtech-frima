@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection
@section('content')
<div class="profile">
    <h2 class="profile__title">プロフィール設定</h2>
    <form class="profile__form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="profile__group profile__group--center">
            <div class="profile__image-preview" id="avatar-preview">
                @if($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" alt="ユーザーアイコン" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @endif
            </div>
            <label class="profile__label-file">
                画像を選択する
                <input type="file" name="image" class="profile__input-file" onchange="previewImage(this)">
            </label>
            @error('image')
            <p class="error-messages">{{ $message }}</p>
            @enderror
        </div>
        <div class="profile__group">
            <label class="profile__label">ユーザー名</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="profile__input">
            @error('name')
            <p class="error-messages">{{ $message }}</p>
            @enderror
        </div>
        <div class="profile__group">
            <label class="profile__label">郵便番号</label>
            <input type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}" class="profile__input">
            @error('postcode')
            <p class="error-messages">{{ $message }}</p>
            @enderror
        </div>
        <div class="profile__group">
            <label class="profile__label">住所</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="profile__input">
            @error('address')
            <p class="error-messages">{{ $message }}</p>
            @enderror
        </div>
        <div class="profile__group">
            <label class="profile__label">建物名</label>
            <input type="text" name="building" value="{{ old('building', $user->building) }}" class="profile__input">
        </div>
        <button type="submit" class="profile__btn">更新する</button>
    </form>
</div>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.querySelector('#avatar-preview');
            preview.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection