<?php

use App\Http\Controllers\PotensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('potensi.index');
    })->name('home');

    Route::get('/dashboard', function () {
        return redirect()->route('potensi.index');
    })->name('dashboard');

    Route::prefix('potensi')->name('potensi.')->whereNumber('potensi')->group(function () {
        Route::get('/', [PotensiController::class, 'index'])->name('index');
        Route::get('create', [PotensiController::class, 'create'])->name('create');
        Route::post('/', [PotensiController::class, 'store'])->name('store');
        Route::get('export', [PotensiController::class, 'export'])->name('export');
        Route::get('{potensi}/sp1', [PotensiController::class, 'downloadSp1'])->name('sp1');
        Route::get('{potensi}/edit', [PotensiController::class, 'edit'])->name('edit');
        Route::get('{potensi}', [PotensiController::class, 'show'])->name('show');
        Route::put('{potensi}', [PotensiController::class, 'update'])->name('update');
        Route::patch('{potensi}', [PotensiController::class, 'update']);
        Route::delete('{potensi}', [PotensiController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
