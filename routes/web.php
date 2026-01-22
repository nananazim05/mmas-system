<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\AttendanceController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;


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
    
    // --- 1. Profil & Umum ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    // --- 2. Import Excel (Kita letak sini je) ---
    Route::post('/staff/import', [UserController::class, 'import'])->name('staff.import');

    // --- 3. Pengurusan Aktiviti (Semua user boleh akses, control dalam controller) ---
    Route::get('/activities', [MeetingController::class, 'index'])->name('activities.index');
    Route::get('/activities/my', [MeetingController::class, 'myActivities'])->name('activities.my');
    Route::get('/activities/create', [MeetingController::class, 'create'])->name('activities.create');
    Route::post('/activities', [MeetingController::class, 'store'])->name('activities.store');
    Route::get('/activities/{meeting}', [MeetingController::class, 'show'])->name('activities.show');
    Route::get('/activities/{meeting}/edit', [MeetingController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{meeting}', [MeetingController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{meeting}', [MeetingController::class, 'destroy'])->name('activities.destroy');

    // --- 4. Fungsi Khas Aktiviti (QR, Report) ---
    Route::get('/activities/summary-report', [MeetingController::class, 'janaLaporan'])->name('activities.summary_report');
    Route::get('/activities/{meeting}/print-qr', [MeetingController::class, 'printQr'])->name('activities.print_qr');
    Route::get('/activities/{meeting}/qr', [MeetingController::class, 'getQr'])->name('activities.get_qr');
    Route::post('/activities/{meeting}/reactivate', [MeetingController::class, 'reactivate'])->name('activities.reactivate');
    Route::get('/activities/{meeting}/report', [MeetingController::class, 'report'])->name('activities.report');
    Route::get('/activities/{meeting}/report/view', [MeetingController::class, 'viewReport'])->name('activities.report.view');

    // --- 5. Pengurusan Staf (Admin) ---
    Route::resource('staff', StaffController::class);
    Route::get('/staff/{user}/report', [StaffController::class, 'report'])->name('staff.report');

});

// 4. ROUTE KEHADIRAN (Boleh diakses Guest & Staf tanpa login session browser)
// Scan & Store diletak di luar 'auth' middleware supaya 'Peserta Luar' boleh akses.

// Route scan QR
Route::get('/attendance/scan/{meeting}/{code}', [AttendanceController::class, 'scan'])
    ->name('attendance.scan')
    ->middleware('signed');

// Route untuk simpan kehadiran (PENTING: Saya buang {meeting} sebab ID dihantar via hidden input)
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');

// 5. Utiliti Lain
// Route Tukar Bahasa (EN / MS)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ms'])) {
        session(['locale' => $locale]); 
    }
    return redirect()->back(); 
})->name('change.lang');

// Route Manual Migration (Hanya untuk Development)
Route::get('/run-migration', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration berjaya dijalankan! Sila semak form aktiviti sekarang.';
});

Route::get('/fix-users-sequence', function () {
    try {
        DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));");
        
        return "Berjaya! Sequence ID users telah dibetulkan. Sila cuba tambah staf semula.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
