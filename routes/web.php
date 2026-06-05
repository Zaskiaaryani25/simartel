<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\P2tlController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController; // Tambahkan import ini
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- HALAMAN AUTH (Terbuka untuk Umum) ---
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

// --- HALAMAN TERPROTEKSI (Wajib Login) ---
Route::middleware(['auth'])->group(function () {

    /**
     * DASHBOARD
     * Logic dipindah ke DashboardController untuk menghindari error $summary
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * MANAGEMENT KARYAWAN (Tetap)
     */
    Route::resource('karyawan', KaryawanController::class)->except(['show']);
    Route::get('/karyawan-sync', [KaryawanController::class, 'syncFromP2tl'])->name('karyawan.sync');
    Route::delete('/karyawan-truncate', [KaryawanController::class, 'truncate'])->name('karyawan.truncate');
    

    Route::get('/ulp', [App\Http\Controllers\DashboardController::class, 'ulpIndex'])->name('ulp.index');

    
    /**
     * P2TL DATA MANAGEMENT (Tetap)
     */
    Route::controller(P2tlController::class)->group(function () {
        Route::get('/p2tl', 'index')->name('p2tl.index');
        Route::post('/p2tl/import', 'import')->name('p2tl.import');
        Route::delete('/p2tl/{id}', 'destroy')->name('p2tl.destroy');
        Route::delete('/p2tl-truncate', 'truncate')->name('p2tl.truncate');
        Route::get('/p2tl/progress', 'getProgress')->name('p2tl.progress');
        Route::post('/p2tl/cancel', 'cancel')->name('p2tl.cancel');
        Route::get('/p2tl/awarding', 'awarding')->name('p2tl.awarding');
    });

    /**
     * USER PROFILE (Tetap)
     */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        
    });

});