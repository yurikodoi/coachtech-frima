<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
Route::get('/', function () {
    $user = Auth::user();
    if ($user && is_null($user->address)) {
        return redirect()->route('profile.edit');
    }
    return app(ItemController::class)->index(request());
})->name('item.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::middleware(['auth'])->group(function () {
    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('like');
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
});
Route::get('/purchase/{itemId}', [ItemController::class, 'showPurchase'])->middleware('auth')->name('purchase.show');
Route::post('/purchase/{itemId}', [ItemController::class, 'storePurchase'])->middleware('auth')->name('purchase.store');
Route::get('/purchase/address/{itemId}', [AddressController::class, 'editAddressPage'])->name('address.edit');
Route::post('/purchase/address/{itemId}', [AddressController::class, 'updateAddress'])->name('address.update');
Route::get('/purchase/success/{item_id}', [ItemController::class, 'successPurchase'])->name('purchase.success');
Route::get('/storage/profiles/{filename}', function ($filename) {
    $path = 'public/profiles/' . $filename;
    if (!Storage::exists($path)) abort(404);
    return response()->file(storage_path('app/' . $path));
});