<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\AttendanceController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

// 1. Lencongan Utama
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Group Wajib Login
Route::middleware('auth')->group(function () {
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengurusan Aktiviti
    Route::get('/activities/my', [MeetingController::class, 'myActivities'])->name('activities.my');
    Route::get('/activities', [MeetingController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [MeetingController::class, 'create'])->name('activities.create');
    Route::post('/activities', [MeetingController::class, 'store'])->name('activities.store');
    Route::get('/activities/{meeting}', [MeetingController::class, 'show'])->name('activities.show');
    
    // Edit & Delete
    Route::get('/activities/{meeting}/edit', [MeetingController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{meeting}', [MeetingController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{meeting}', [MeetingController::class, 'destroy'])->name('activities.destroy');

    // Pengurusan Staf
    Route::resource('staff', StaffController::class);

    // Route untuk AJAX QR Code
    Route::get('/activities/{meeting}/qr', [MeetingController::class, 'getQr'])->name('activities.get_qr');

    // Route untuk Aktifkan Semula
    Route::post('/activities/{meeting}/reactivate', [MeetingController::class, 'reactivate'])->name('activities.reactivate');

    // Route Download PDF Staf
    Route::get('/staff/{user}/report', [StaffController::class, 'report'])->name('staff.report');

    // Route Download PDF 
    Route::get('/activities/{meeting}/report', [MeetingController::class, 'report'])->name('activities.report');
    
    // Route Lihat PDF (Stream)
    Route::get('/activities/{meeting}/report/view', [MeetingController::class, 'viewReport'])->name('activities.report.view');

});

// 4. ROUTE KEHADIRAN 
Route::get('/attendance/scan/{meeting}/{code}', [AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/attendance/store/{meeting}', [AttendanceController::class, 'store'])->name('attendance.store');

// Route Tukar Bahasa (EN / MS)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ms'])) {
        session(['locale' => $locale]); // Simpan pilihan dalam session
    }
    return redirect()->back(); // Kembali ke halaman asal
})->name('change.lang');

Route::get('/run-migration', function () {
    //paksa Laravel jalankan fail migration 'fix' yang tertunggak
    Artisan::call('migrate', ['--force' => true]);
    
    return 'Migration berjaya dijalankan! Sila semak form aktiviti sekarang.';
});

require __DIR__.'/auth.php';