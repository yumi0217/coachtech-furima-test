<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AddressController; // AddressControllerをuseする
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
});

Route::get('/test-mylist', [\App\Http\Controllers\ItemController::class, 'testMylist']);
Route::post('/purchase/checkout/{method}/{item}', [PurchaseController::class, 'redirectToCheckout'])->name('purchase.checkout');


// プロフィール編集
Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware(['auth'])
    ->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])
    ->middleware(['auth'])
    ->name('profile.update');
Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');

Route::middleware(['auth'])->group(function () {
    // 編集画面表示（GET）
    Route::get('/addresses/update', [AddressController::class, 'edit'])->name('addresses.edit');

    // 住所更新処理（POST）
    Route::post('/addresses/update', [AddressController::class, 'update'])->name('addresses.update');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::middleware(['auth'])->group(function () {
    // 認証待ち画面
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    // 認証メール再送
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送信しました。');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // 認証リンククリック後の処理
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('profile.edit');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    // ★ お気に入りの追加・解除
    Route::post('/items/{item}/favorite', [FavoriteController::class, 'toggle'])
        ->middleware('auth')
        ->name('favorites.toggle');
});
