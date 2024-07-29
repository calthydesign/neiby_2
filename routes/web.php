<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConstructionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChatController;

// ルートページ: ログイン状態によってリダイレクト
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard'); // ダッシュボードにリダイレクト
    } else {
        return redirect()->route('login'); // ログインページにリダイレクト
    }
})->name('home');

// ダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // 診断関連のルート
    Route::prefix('diagnoses')->name('diagnoses.')->group(function () {
        Route::get('/', [DiagnosisController::class, 'index'])->name('index');
        Route::post('/', [DiagnosisController::class, 'store'])->name('store');
        Route::delete('/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('destroy');
        Route::get('/{diagnosis}/edit', [DiagnosisController::class, 'edit'])->name('edit');
        Route::post('/{diagnosis}/edit', [DiagnosisController::class, 'edit'])->name('edit.post');
        Route::post('/update', [DiagnosisController::class, 'update'])->name('update');
    });

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ポスト関連のルート
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');  // PUTメソッドに変更
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

    // 気血水のデータ表示
    Route::get('/constructions/{id}', [ConstructionController::class, 'show'])->name('constructions.show');
});

// チャット関連のルート
Route::get('/chat', [ChatController::class, 'chat'])->name('chat.create');
Route::post('/chat', [ChatController::class, 'chat'])->name('chat.post');

require __DIR__.'/auth.php';