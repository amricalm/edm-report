<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AmanController;
use App\Http\Controllers\RekapProgramController;
use App\Http\Controllers\RekapProgramDtlController;
use App\Http\Controllers\RekapProgramMatrikController;
use App\Http\Controllers\RekapProjectController;
use App\Http\Controllers\NotifikasiController;


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

Route::get('/notif',  [NotifikasiController::class, 'index']);

Route::get('/',  [DashboardController::class, 'index'])->name("beranda");
Route::get('/keluar', [AmanController::class, 'logout'])->name('aman.keluar');
Route::post('aman/validasi', [AmanController::class, 'validasi']);
Route::get('/aman', [AmanController::class, 'index']);
Route::get('/aman', [AmanController::class, 'index'])->name('aman');

//============================= ROUTE LAPORAN KEUANGAN PROGRAM ============================//
Route::get('/rekapkeuanganprogram', [RekapProgramController::class, 'index']);
Route::post('/getrekapkeuanganprogram', [RekapProgramController::class, 'get'])->name('get.program.tbl');
Route::get('/rekapkeuanganprogramXls', [RekapProgramController::class, 'exportXls'])->name('export.program.xls');
//===================== ROUTE END LAPORAN KEUANGAN PROGRAM ===========================//

//============================= ROUTE LAPORAN KEUANGAN PROGRAM DETAIL ============================//
Route::get('/rekapkeuanganprogramdtl', [RekapProgramDtlController::class, 'index']);
Route::post('/getrekapkeuanganprogramdtl', [RekapProgramDtlController::class, 'get'])->name('get.programdtl.tbl');
Route::get('/rekapkeuanganprogramdtlXls', [RekapProgramDtlController::class, 'exportXls'])->name('export.programdtl.xls');
//===================== ROUTE END LAPORAN KEUANGAN PROGRAM DETAIL ===========================//

//============================= ROUTE LAPORAN KEUANGAN PROGRAM MATRIK ============================//
Route::get('/rekapkeuanganmatrik', [RekapProgramMatrikController::class, 'index']);
Route::post('/getrekapkeuanganmatrik', [RekapProgramMatrikController::class, 'get'])->name('get.matrik.tbl');
Route::get('/rekapkeuanganmatrikXls', [RekapProgramMatrikController::class, 'exportXls'])->name('export.matrik.xls');
//===================== ROUTE END LAPORAN KEUANGAN PROGRAM MATRIK ===========================//

//============================= ROUTE LAPORAN KEUANGAN PROGRAM DETAIL ============================//
Route::get('/rekapkeuanganproject', [RekapProjectController::class, 'index']);
Route::post('/getrekapkeuanganprojectdtl', [RekapProjectController::class, 'get'])->name('get.project.tbl');
Route::get('/rekapkeuanganprojectXls', [RekapProjectController::class, 'exportXls'])->name('export.project.xls');
//===================== ROUTE END LAPORAN KEUANGAN PROGRAM DETAIL ===========================//

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

#URL::forceScheme('https');
