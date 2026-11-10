<?php

use App\Http\Controllers\DetectionController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\StatsController;
use App\Http\Middleware\TrackVisitor;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::middleware(TrackVisitor::class)->group(function () {
    Route::get('/', [DetectionController::class, 'home'])->name('beranda');
    Route::get('/deteksi', [DetectionController::class, 'create'])->name('deteksi');
    Route::post('/deteksi', [DetectionController::class, 'store'])->name('deteksi.store');
    Route::get('/hasil/{analysis}', [DetectionController::class, 'show'])->name('hasil');
    Route::get('/riwayat', [DetectionController::class, 'history'])->name('riwayat');
    Route::delete('/riwayat/{analysis}', [DetectionController::class, 'destroy'])->name('riwayat.hapus');
    Route::get('/statistik', StatsController::class)->name('statistik');
});

// Foto dari database: tanpa session/cookie agar bisa di-cache CDN dan tidak dihitung sebagai kunjungan.
Route::get('/foto/{name}', PhotoController::class)
    ->where('name', '[A-Za-z0-9\-]+\.[a-z]+')
    ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, AddQueuedCookiesToResponse::class])
    ->name('foto');
