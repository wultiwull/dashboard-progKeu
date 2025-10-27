<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SatkerController;
use App\Imports\InpresImports;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;


// =====================
// DASHBOARD ROUTES
// =====================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/laporan', [DashboardController::class, 'index'])->name('dashboard.bbws');
Route::get('/dashboard/laporan/print', [DashboardController::class, 'print'])->name('dashboard.bbws.print');


// =====================
// IMPORT & PROGRESS ROUTES
// =====================
Route::prefix('progress')->group(function () {

    // Upload Excel
    Route::post('/upload', [ImportController::class, 'uploadExcel'])->name('progress.upload');
    Route::get('/download-template', [ImportController::class, 'downloadTemplate'])->name('progress.download-template');

    // Rekap Progress per Minggu
    Route::get('/rekap', [ImportController::class, 'listRekap'])->name('progress.list-rekap');
    Route::get('/rekap/view/{reportDate}', [ImportController::class, 'viewRekap'])->name('progress.view-rekap');
    Route::get('/rekap/download/{reportDate}', [ImportController::class, 'downloadRekap'])->name('progress.rekap.download');

});


// =====================
// REKAP ROUTES
// =====================
Route::prefix('rekap')->group(function () {
    Route::get('/', [RekapController::class, 'index'])->name('rekap.index'); // Halaman utama rekap
    Route::get('/download/{reportDate?}', [RekapController::class, 'downloadPdf'])->name('rekap.download.pdf'); // Cetak ke PDF
    Route::get('/view/{reportDate?}', [RekapController::class, 'view'])->name('rekap.view'); // Lihat detail laporan mingguan
    Route::get('/{date}', [RekapController::class, 'show'])->name('rekap.show'); // Lihat rekap per tanggal
});

// =====================
// REKAP ROUTES
// =====================
Route::prefix('dashboard')->group(function () {
    Route::get('/manual-input', [ManualInputController::class, 'index'])->name('dashboard.manual-input');
    Route::post('/manual-input', [ManualInputController::class, 'store'])->name('dashboard.manual-input.store');
});

