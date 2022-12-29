<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AmanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TemporaryController;
use App\Http\Controllers\MProgramController;
use App\Http\Controllers\MProjectController;
use App\Http\Controllers\MSalesController;
use App\Http\Controllers\MDonaturController;
use App\Http\Controllers\JaringanController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\ScPenggunaController;
use App\Http\Controllers\ScGroupController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\BukuBankController;
use App\Http\Controllers\LaporanController;
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

//============================= Temporary Link ============================//
//Menu Donasi
Route::get('/donasi/create', [DonasiController::class, 'create']);
Route::get('/donasi/create/{id}', [DonasiController::class, 'create']);
Route::get('/donasi', [DonasiController::class, 'index']);
Route::post('/donasi/getTabel', [DonasiController::class, 'getTabel'])->name('donasi.tbl');

Route::get('/bukubank', [BukuBankController::class, 'index']);
Route::post('/bukubank/getTabel', [BukuBankController::class, 'getTabel'])->name('bukubank.tbl');

Route::get('/bukuedc', [TemporaryController::class, 'bukuedc']);
Route::get('/donasi/konfirmasi', [TemporaryController::class, 'konfirmasi']);

Route::post('/donasi/getJaringan', [DonasiController::class, 'getJaringan'])->name('dns.get.jaringan');
Route::post('/donasi/getSales', [DonasiController::class, 'getSales'])->name('dns.get.sales');
Route::post('/donasi/getTabelDonatur', [DonasiController::class, 'getTabelDonatur'])->name('dns.tbl.donatur');


//Menu Donatur
Route::get('/donatur', [MDonaturController::class, 'index']);
Route::post('/donatur/get', [MDonaturController::class, 'get'])->name('donatur.get');
Route::post('/donatur/getTabel', [MDonaturController::class, 'getTabel'])->name('donatur.tbl');
Route::get('/settelemarketer', [TemporaryController::class, 'settelemarketer']);

//Menu Integrasi
Route::get('/telemarketing', [TemporaryController::class, 'donatur']);

//Menu Data Induk
Route::get('/project', [TemporaryController::class, 'project']);
Route::get('/salesman', [TemporaryController::class, 'salesman']);
Route::get('/jaringan', [TemporaryController::class, 'jaringan']);
Route::get('/cabang', [TemporaryController::class, 'cabang']);
Route::get('/kas', [TemporaryController::class, 'kas']);

//Menu Keamanan
Route::get('/scpengguna', [ScPenggunaController::class, 'index']);
Route::post('/scpengguna/getTabel', [ScPenggunaController::class, 'getTabel'])->name('scpengguna.getTabel');
Route::post('/scpengguna/get', [ScPenggunaController::class, 'get'])->name('scpengguna.get');
Route::post('/scpengguna', [ScPenggunaController::class, 'save'])->name('scpengguna.save');
Route::post('/scpengguna/delete', [ScPenggunaController::class, 'delete'])->name('scpengguna.delete');
Route::post('/scpengguna/isExist', [ScPenggunaController::class, 'isExist'])->name('scpengguna.isExist');

Route::get('/scgroup', [ScGroupController::class, 'index']);
Route::post('scgroup/getTabel', [ScGroupController::class, 'getTabel']);
Route::post('/scgroup/get', [ScGroupController::class, 'get'])->name('scgroup.get');
Route::post('/scgroup', [ScGroupController::class, 'save'])->name('scgroup.save');
Route::post('/scgroup/delete', [ScGroupController::class, 'delete'])->name('scgroup.delete');
Route::post('/scgroup/isExist', [ScGroupController::class, 'isExist'])->name('scgroup.isExist');

Route::get('/scgroup/role', [ScGroupController::class, 'getRole'])->name('scgroup.role');
Route::post('scgroup/getTabelRole', [ScGroupController::class, 'getTabelRole']);
Route::post('scgroup/saveTabelRole', [ScGroupController::class, 'saveRole'])->name('scrole.save');

//Menu Konfigurasi
Route::get('/setting', [SettingController::class, 'index']);
Route::post('/setting', [SettingController::class, 'store'])->name("setting.store");

//===================== Temporary Link ===========================//

//============================= PROGRAM ============================//
Route::get('/mprogram', [MProgramController::class, 'index']);
Route::post('mprogram/getTabel', [MProgramController::class, 'getTabel']);
Route::post('/mprogram/get', [MProgramController::class, 'get'])->name('mprogram.get');
Route::post('/mprogram', [MProgramController::class, 'save'])->name('mprogram.save');
Route::post('/mprogram/delete', [MProgramController::class, 'delete'])->name('mprogram.delete');
Route::post('/mprogram/isExist', [MProgramController::class, 'isExist'])->name('mprogram.isExist');
//===================== ROUTE END PROGRAM ===========================//

//============================= PROJECT ============================//
Route::get('/mproject', [MProjectController::class, 'index']);
Route::post('mproject/getTabel', [MProjectController::class, 'getTabel'])->name('mproject.getTabel');
Route::post('/mproject/get', [MProjectController::class, 'get'])->name('mproject.get');
Route::post('/mproject', [MProjectController::class, 'save'])->name('mproject.save');
Route::post('/mproject/delete', [MProjectController::class, 'delete'])->name('mproject.delete');
Route::post('/mproject/isExist', [MProjectController::class, 'isExist'])->name('mproject.isExist');
//===================== ROUTE END PROJECT ===========================//

//============================= TELEAGENT ============================//
Route::get('/salesman', [MSalesController::class, 'index']);
Route::post('/salesman/getTabel', [MSalesController::class, 'getTabel'])->name('msales.getTabel');
Route::post('/salesman/get', [MSalesController::class, 'get'])->name('msales.get');
Route::post('/salesman', [MSalesController::class, 'save'])->name('msales.save');
Route::post('/salesman/delete', [MSalesController::class, 'delete'])->name('msales.delete');
Route::post('/salesman/isExist', [MSalesController::class, 'isExist'])->name('msales.isExist');
Route::post('/salesman/getByCabang', [MSalesController::class, 'getByCabang']);
//===================== ROUTE END TELEAGENT ===========================//

//============================= JARINGAN ============================//
Route::get('/jaringan', [JaringanController::class, 'index']);
Route::post('jaringan/getTabel', [JaringanController::class, 'getTabel']);
Route::post('/jaringan/get', [JaringanController::class, 'get'])->name('jaringan.get');
Route::post('/jaringan', [JaringanController::class, 'save'])->name('jaringan.save');
Route::post('/jaringan/delete', [JaringanController::class, 'delete'])->name('jaringan.delete');
Route::post('/jaringan/isExist', [JaringanController::class, 'isExist'])->name('jaringan.isExist');
Route::post('/jaringan/getByCabang', [JaringanController::class, 'getByCabang']);
//===================== ROUTE END JARINGAN ===========================//

//============================= CABANG ============================//
Route::get('/cabang', [CabangController::class, 'index']);
Route::post('cabang/getTabel', [CabangController::class, 'getTabel']);
Route::post('/cabang/get', [CabangController::class, 'get'])->name('cabang.get');
Route::post('/cabang', [CabangController::class, 'save'])->name('cabang.save');
Route::post('/cabang/delete', [CabangController::class, 'delete'])->name('cabang.delete');
Route::post('/cabang/isExist', [CabangController::class, 'isExist'])->name('cabang.isExist');
//===================== ROUTE END CABANG ===========================//

//============================= KAS ============================//
Route::get('/kas', [KasController::class, 'index']);
Route::post('kas/getTabel', [KasController::class, 'getTabel']);
Route::post('/kas/get', [KasController::class, 'get'])->name('kas.get');
Route::post('/kas', [KasController::class, 'save'])->name('kas.save');
Route::post('/kas/delete', [KasController::class, 'delete'])->name('kas.delete');
Route::post('/kas/isExist', [KasController::class, 'isExist'])->name('kas.isExist');
//===================== ROUTE END KAS ===========================//

//============================= INPUT DONASI ============================//
Route::get('/donasi/create', [DonasiController::class, 'create']);
Route::post('/donasi/validasi', [DonasiController::class, 'validasi']);
Route::post('/donasi/cariNoKwitansi', [DonasiController::class, 'cariNoKwitansi']);
Route::post('/donasi/cariNoHp', [DonasiController::class, 'cariNoHp']);
Route::post('/donasi/cariEmail', [DonasiController::class, 'cariEmail']);
Route::post('/donasi/simpan', [DonasiController::class, 'save']);
Route::post('/donasi/delete', [DonasiController::class, 'delete'])->name('donasi.delete');
Route::get('/donasi/create/getDana/{kdProgram}', [DonasiController::class, 'getDana']);
//===================== ROUTE END INPUT DONASI ===========================//

//============================= LAPORAN DONASI ============================//
Route::get('/laporan/donasi', [LaporanController::class, 'donasi']);
Route::post('/laporan/getDonasi', [LaporanController::class, 'getLapDonasi'])->name('lap.getDonasi.tbl');
Route::get('/laporan/donasiXls', [LaporanController::class, 'donasiExportXls'])->name('lap.donasi.xls');
//===================== ROUTE END LAPORAN DONASI ===========================//

//============================= LAPORAN DONASI PER REKENING ============================//
Route::get('/laporan/donasiperrekening', [LaporanController::class, 'donasiperrekening']);
Route::post('/laporan/getDonasiPerRekening', [LaporanController::class, 'getLapDonasiPerRekening'])->name('lap.getDonasiPerRekening.tbl');
Route::get('/laporan/donasiPerRekeningXls', [LaporanController::class, 'donasiPerRekeningExportXls'])->name('lap.donasiPerRekening.xls');
//===================== ROUTE END LAPORAN DONASI PER REKENING ===========================//

//============================= LAPORAN DONASI PER JARINGAN ============================//
Route::get('/laporan/donasiperjaringan', [LaporanController::class, 'donasiperjaringan']);
Route::post('/laporan/getDonasiPerJaringan', [LaporanController::class, 'getLapDonasiPerJaringan'])->name('lap.getDonasiPerJaringan.tbl');
Route::get('/laporan/donasiPerJaringanXls', [LaporanController::class, 'donasiPerJaringanExportXls'])->name('lap.donasiPerJaringan.xls');
//===================== ROUTE END LAPORAN DONASI PER JARINGAN ===========================//

//============================= LAPORAN DONASI PER FUNDRAISER ============================//
Route::get('/laporan/donasiperfundraiser', [LaporanController::class, 'donasiperfundraiser']);
Route::post('/laporan/getDonasiPerFundraiser', [LaporanController::class, 'getLapDonasiPerFundraiser'])->name('lap.getDonasiPerFundraiser.tbl');
Route::get('/laporan/donasiPerFundraiserXls', [LaporanController::class, 'donasiPerFundraiserExportXls'])->name('lap.donasiPerFundraiser.xls');
//===================== ROUTE END LAPORAN DONASI PER FUNDRAISER ===========================//


//============================= LAPORAN DONASI PER PROJECT ============================//
Route::get('/laporan/donasiperproject', [LaporanController::class, 'donasiperproject']);
Route::post('/laporan/getDonasiPerProject', [LaporanController::class, 'getLapDonasiPerProject'])->name('lap.getDonasiPerProject.tbl');
Route::get('/laporan/donasiPerProjectXls', [LaporanController::class, 'donasiPerProjectExportXls'])->name('lap.donasiPerProject.xls');
//===================== ROUTE END LAPORAN DONASI PER PROJECT ===========================//

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

#URL::forceScheme('https');