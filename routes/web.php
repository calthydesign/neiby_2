<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiagnosisController;

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

    // ダッシュボード
    Route::get('/dashboard', [DiagnosisController::class, 'index'])->name('dashboard');

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // チャット関連のルート
    Route::get('/chat', [ChatController::class, 'chat'])->name('chat.create');
    Route::post('/chat', [ChatController::class, 'chat'])->name('chat.post');
});

// ルートページを認証済みユーザーのみアクセス可能に
Route::get('/', [DiagnosisController::class, 'index'])->middleware(['auth'])->name('home');

require __DIR__.'/auth.php';