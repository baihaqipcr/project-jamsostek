<?php

use App\Http\Controllers\PotensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('potensi.index');
    });

    Route::get('/dashboard', function () {
        return redirect()->route('potensi.index');
    })->name('dashboard');

    Route::get('potensi/{potensi}/sp1', [PotensiController::class, 'downloadSp1'])->name('potensi.sp1');
    Route::get('potensi/export', [PotensiController::class, 'export'])->name('potensi.export');
    Route::resource('potensi', PotensiController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
