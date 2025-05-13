<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\DetailDonasiController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\DetailPendapatanController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\KlaimMerchandiseController;
use App\Http\Controllers\KomisiController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipanBarangController;
use App\Http\Controllers\PenitipController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/registerPenitip', [PenitipController::class, 'register']);
Route::post('/registerPegawai', [PegawaiController::class, 'register']);
Route::post('/registerPembeli', [PembeliController::class, 'register']);
Route::post('/registerOrganisasi', [OrganisasiController::class, 'register']);

Route::post('/loginPenitip', [PenitipController::class, 'login']);
Route::post('/loginPegawai', [PegawaiController::class, 'login']);
Route::post('/loginPembeli', [PembeliController::class, 'login']);
Route::post('/loginOrganisasi', [OrganisasiController::class, 'login']);

Route::get('/KategoriBarang', [KategoriBarangController::class, 'index']);
Route::get('/KategoriBarang/all', [KategoriBarangController::class, 'getData']);
Route::get('/KategoriBarang/{id}', [KategoriBarangController::class, 'show']);

Route::get('/Merchandise', [MerchandiseController::class, 'index']);
Route::get('/Merchandise/all', [MerchandiseController::class, 'getData']);
Route::get('/Merchandise/{id}', [MerchandiseController::class, 'show']);

Route::get('/PenitipanBarang', [PenitipanBarangController::class, 'index']);
Route::get('/PenitipanBarang/all', [PenitipanBarangController::class, 'getData']);
Route::get('/PenitipanBarang/Count', [PenitipanBarangController::class, 'countPenitipanBarang']);
Route::get('/PenitipanBarang/{id}', [PenitipanBarangController::class, 'show']);

Route::get('/Alamat', [AlamatController::class, 'index']);
Route::get('/Alamat/all', [AlamatController::class, 'getData']);
Route::get('/Alamat/{id}', [AlamatController::class, 'show']);

Route::get('/Jabatan', [JabatanController::class, 'index']);
Route::get('/Jabatan/all', [JabatanController::class, 'getData']);
Route::get('/Jabatan/{id}', [JabatanController::class, 'show']);

Route::get('/Diskusi', [DiskusiController::class, 'index']);
Route::get('/Diskusi/all', [DiskusiController::class, 'getData']);
Route::get('/Diskusi/Barang/{id}', [DiskusiController::class, 'getDataByBarangId']);
Route::get('/Diskusi/{id}', [DiskusiController::class, 'show']);

Route::get('/Komisi', [KomisiController::class, 'index']);
Route::get('/Komisi/all', [KomisiController::class, 'getData']);
Route::get('/Komisi/{id}', [KomisiController::class, 'show']);

Route::get('/KlaimMerchandise', [KlaimMerchandiseController::class, 'index']);
Route::get('/KlaimMerchandise/all', [KlaimMerchandiseController::class, 'getData']);
Route::get('/KlaimMerchandise/{id}', [KlaimMerchandiseController::class, 'show']);

Route::get('/DetailDonasi', [DetailDonasiController::class, 'index']);
Route::get('/DetailDonasi/all', [DetailDonasiController::class, 'getData']);
Route::get('/DetailDonasi/{id}', [DetailDonasiController::class, 'show']);

Route::get('/DetailPendapatan', [DetailPendapatanController::class, 'index']);
Route::get('/DetailPendapatan/all', [DetailPendapatanController::class, 'getData']);
Route::get('/DetailPendapatan/{id}', [DetailPendapatanController::class, 'show']);

Route::get('/DetailPembelian', [DetailPembelianController::class, 'index']);
Route::get('/DetailPembelian/all', [DetailPembelianController::class, 'getData']);
Route::get('/DetailPembelian/{id}', [DetailPembelianController::class, 'show']);

Route::get('/Donasi', [DonasiController::class, 'index']);
Route::get('/Donasi/all', [DonasiController::class, 'getData']);
Route::get('/Donasi/Count', [DonasiController::class, 'countDonasi']);
Route::get('/Donasi/{id}', [DonasiController::class, 'show']);

Route::get('/Pembelian', [PembelianController::class, 'index']);
Route::get('/Pembelian/all', [PembelianController::class, 'getData']);
Route::get('/Pembelian/Count', [PembelianController::class, 'countPembelian']);
Route::get('/Pembelian/{id}', [PembelianController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/EditUserPenitip', [PenitipController::class, 'update']);
    Route::post('/logoutPenitip', [PenitipController::class, 'logout']);

    Route::post('/EditUserPembeli', [PembeliController::class, 'update']);
    Route::post('/logoutPembeli', [PembeliController::class, 'logout']);

    Route::post('/EditOrganisasi', [OrganisasiController::class, 'update']);
    Route::post('/logoutOrganisasi', [OrganisasiController::class, 'logout']);

    Route::post('/EditPegawai', [PegawaiController::class, 'update']);
    Route::post('/logoutPegawai', [PegawaiController::class, 'logout']);

    //Penitipan Barang
    Route::post('/PenitipanBarang', [PenitipanBarangController::class, 'store']);
    Route::post('/PenitipanBarang/dashboard', [PenitipanBarangController::class, 'storeDashboard']);
    // Route::get('/PenitipanBarang/User/Count', [PenitipanBarangController::class, 'countPenitipanBarangByUser']);
    Route::post('/PenitipanBarang/{id}', [PenitipanBarangController::class, 'update']);
    Route::post('/PenitipanBarang/dashboard/{id}', [PenitipanBarangController::class, 'updateDashboard']);
    Route::get('/PenitipanBarang/user/{id}', [PenitipanBarangController::class, 'showPenitipanBarangbyPenitip']);
    Route::get('/PenitipanBarang/user/{id}', [PenitipanBarangController::class, 'showPenitipanBarangbyPembeli']);
    Route::get('/PenitipanBarang/user', [PenitipanBarangController::class, 'getDataByPenitipId']);
    Route::post('/PenitipanBarang/UpdateRating', [PenitipanBarangController::class, 'UpdateRating']);
    Route::delete('/PenitipanBarang/{id}', [PenitipanBarangController::class, 'destroy']);
    
    //Donasi
    Route::post('/Donasi', [DonasiController::class, 'store']);
    Route::post('/Donasi/dashboard', [DonasiController::class, 'storeDashboard']);
    Route::post('/Donasi/{id}', [DonasiController::class, 'update']);
    Route::post('/Donasi/Korfirm/{id}', [DonasiController::class, 'UpdateKorfirmasi']);
    Route::post('/Donasi/Details/{id}', [DonasiController::class, 'UpdateDetailDonasi']);
    Route::post('/Donasi/dashboard/{id}', [DonasiController::class, 'updateDashboard']);
    Route::get('/Donasi/Organisasi/{id}', [DonasiController::class, 'getDataByOrganisasiId']);
    Route::get('/Donasi/Organisasi/Count', [DonasiController::class, 'countDonasi']);
    Route::get('/Donasi/Organisasi/Count/{id}', [DonasiController::class, 'countDonasiByOrganisasi']);
    Route::delete('/Donasi/{id}', [DonasiController::class, 'destroy']);

    //KategoriBarang
    Route::post('/KategoriBarang', [KategoriBarangController::class, 'store']);
    Route::post('/KategoriBarang/{id}', [KategoriBarangController::class, 'update']);
    Route::get('/KategoriBarang/PenitipanBarang/{id}', [KategoriBarangController::class, 'showKategoriBarangWithPenitipanBarangByKategori_BarangId']);
    Route::get('/KategoriBarang/PenitipanBarang/', [KategoriBarangController::class, 'showKategoriBarangWithPenitipanBarang']);
    Route::delete('/KategoriBarang/{id}', [KategoriBarangController::class, 'destroy']);
   
    //Jabatan
    Route::post('/Jabatan', [JabatanController::class, 'store']);
    Route::post('/Jabatan/{id}', [JabatanController::class, 'update']);
    Route::get('/Jabatan/Pegawai/{id}', [JabatanController::class, 'showJabatanWithPegawaiByJabatanId']);
    Route::get('/Jabatan/Pegawai', [JabatanController::class, 'showJabatanWithPegawai']);
    Route::delete('/Jabatan/{id}', [JabatanController::class, 'destroy']);

    //Alamat
    Route::post('/Alamat', [AlamatController::class, 'store']);
    Route::post('/Alamat/Pembeli', [AlamatController::class, 'AddAlamatPembeli']);
    Route::post('/Alamat/{id}', [AlamatController::class, 'update']);
    Route::post('/Alamat/Pembeli/{id}', [AlamatController::class, 'EditAlamatPembeli']);
    Route::get('/Alamat/Pembeli/{id}', [AlamatController::class, 'showAlamatbyPembeli']);
    Route::get('/Alamat', [AlamatController::class, 'getData']);
    Route::delete('/Alamat/{id}', [AlamatController::class, 'destroy']);

    //DetailDonasi
    Route::post('/DetailDonasi', [DetailDonasiController::class, 'store']);
    Route::post('/DetailDonasi/{id}', [DetailDonasiController::class, 'update']);
    Route::get('/DetailDonasi/user/{id}', [DetailDonasiController::class, 'showDetailDonasibyUser']);
    Route::delete('/DetailDonasi/{id}', [DetailDonasiController::class, 'destroy']);

    //DetailPendapatan
    Route::post('/DetailPendapatan', [DetailPendapatanController::class, 'store']);
    Route::post('/DetailPendapatan/{id}', [DetailPendapatanController::class, 'update']);
    Route::get('/DetailPendapatan/user/{id}', [DetailPendapatanController::class, 'showDetailPendapatanbyUser']);
    Route::delete('/DetailPendapatan/{id}', [DetailPendapatanController::class, 'destroy']);

    //DetailPembelian
    Route::post('/DetailPembelian', [DetailPembelianController::class, 'store']);
    Route::post('/DetailPembelian/{id}', [DetailPembelianController::class, 'update']);
    Route::get('/DetailPembelian/user/{id}', [DetailPembelianController::class, 'showDetailPembelianbyUser']);
    Route::delete('/DetailPembelian/{id}', [DetailPembelianController::class, 'destroy']);

    //Komisi
    Route::post('/Komisi', [KomisiController::class, 'store']);
    Route::post('/Komisi/{id}', [KomisiController::class, 'update']);
    Route::get('/Komisi/Pegawai/{id}', [KomisiController::class, 'showKomisibyPegawai']);
    Route::get('/Komisi/Penitip/{id}', [KomisiController::class, 'showKomisibyPenitip']);
    Route::delete('/Komisi/{id}', [KomisiController::class, 'destroy']);

    //KlaimMerchandise
    Route::post('/KlaimMerchandise', [KlaimMerchandiseController::class, 'store']);
    Route::post('/KlaimMerchandise/Dashboard', [KlaimMerchandiseController::class, 'storeDashboard']);
    Route::post('/KlaimMerchandise/{id}', [KlaimMerchandiseController::class, 'update']);
    Route::post('/KlaimMerchandise/Dashboard/{id}', [KlaimMerchandiseController::class, 'updateDashboard']);
    Route::post('/KlaimMerchandise/Status/{id}', [KlaimMerchandiseController::class, 'UpdateStatus']);
    Route::get('/KlaimMerchandise/Penitip/{id}', [KlaimMerchandiseController::class, 'getDataByPenitipId']);
    Route::get('/KlaimMerchandise/Pembeli/{id}', [KlaimMerchandiseController::class, 'getDataByPembeliId']);
    Route::get('/KlaimMerchandise/Count', [KlaimMerchandiseController::class, 'countKlaim']);
    Route::get('/KlaimMerchandise/Count/Penitip/{id}', [KlaimMerchandiseController::class, 'countKlaimByPenitip']);
    Route::get('/KlaimMerchandise/Count/Pembeli/{id}', [KlaimMerchandiseController::class, 'countKlaimByPembeli']);
    Route::delete('/KlaimMerchandise/{id}', [KlaimMerchandiseController::class, 'destroy']);

    //Diskusi
    Route::post('/Diskusi', [DiskusiController::class, 'store']);
    Route::post('/Diskusi/Dashboard', [DiskusiController::class, 'storeDashboard']);
    Route::post('/Diskusi/{id}', [DiskusiController::class, 'update']);
    Route::post('/Diskusi/Dashboard/{id}', [DiskusiController::class, 'updateDashboard']);
    Route::get('/Diskusi/Penitip/{id}', [DiskusiController::class, 'getDataByPenitipId']);
    Route::get('/Diskusi/Pembeli/{id}', [DiskusiController::class, 'getDataByPembeliId']);
    Route::get('/Diskusi/Pegawai/{id}', [DiskusiController::class, 'getDataByPegawaiId']);
    
    Route::delete('/Diskusi/{id}', [DiskusiController::class, 'destroy']);
    
    //Merchandise
    Route::post('/Merchandise', [MerchandiseController::class, 'store']);
    Route::post('/Merchandise/{id}', [MerchandiseController::class, 'update']);
    Route::get('/Merchandise/Penitip/{id}', [MerchandiseController::class, 'showMerchandisebyPenitip']);
    Route::get('/Merchandise/Pembeli/{id}', [MerchandiseController::class, 'showMerchandisebyPembeli']);
    Route::delete('/Merchandise/{id}', [MerchandiseController::class, 'destroy']);
    
    //Pembelian
    Route::post('/Pembelian', [PembelianController::class, 'store']);
    Route::post('/Pembelian/dashboard', [PembelianController::class, 'storeDashboard']);
    Route::post('/Pembelian/Bukti/{id}', [PembelianController::class, 'InsertBukti']);
    Route::post('/Pembelian/Konfirm/{id}', [PembelianController::class, 'KonfirmasiPembelian']);
    Route::post('/Pembelian/{id}', [PembelianController::class, 'update']);
    Route::post('/Pembelian/dashboard/{id}', [PembelianController::class, 'updateDashboard']);
    Route::get('/Pembelian/Pembeli/{id}', [PembelianController::class, 'showPembelianbyUser']);
    Route::get('/Pembelian/Pembeli', [PembelianController::class, 'getDataByPembeliId']);
    Route::get('/Pembelian/Count', [PembelianController::class, 'countPembelian']);
    Route::get('/Pembelian/Pembeli/Count', [PembelianController::class, 'countPembelianByPembeli']);
    Route::delete('/Pembelian/{id}', [PembelianController::class, 'destroy']);
;
    //Penitip
    Route::get('/Penitip', [PenitipController::class, 'index']);
    Route::get('/Penitip/all', [PenitipController::class, 'getData']);
    Route::get('/Penitip/Count', [PenitipController::class, 'countPenitip']);
    Route::get('/PenitipData', [PenitipController::class, 'show']);
    Route::post('/Penitip', [PenitipController::class, 'store']);
    Route::post('/Penitip/{id}', [PenitipController::class, 'update']);
    Route::post('/PenitipEditData', [PenitipController::class, 'edit']);
    Route::post('/resetPasswordPenitip/{id}', [PenitipController::class, 'resetPassword']);
    Route::post('/sendResetLinkPenitip', [PenitipController::class, 'sendResetLink']);
    Route::get('/Penitip/Penitip/{id}', [PenitipController::class, 'showPenitipbyPenitip']);
    Route::delete('/Penitip/{id}', [PenitipController::class, 'destroy']);

    //Pembeli
    Route::get('/Pembeli', [PembeliController::class, 'index']);
    Route::get('/Pembeli/all', [PembeliController::class, 'getData']);
    Route::get('/Pembeli/Count', [PembeliController::class, 'countPembeli']);
    Route::get('/PembeliData', [PembeliController::class, 'show']);
    Route::post('/Pembeli', [PembeliController::class, 'store']);
    Route::post('/Pembeli/{id}', [PembeliController::class, 'update']);
    Route::post('/PembeliEditData', [PembeliController::class, 'edit']);
      Route::post('/resetPasswordPembeli/{id}', [PembeliController::class, 'resetPassword']);
    Route::post('/sendResetLinkPembeli', [PembeliController::class, 'sendResetLink']);
    Route::get('/Pembeli/Pembeli/{id}', [PembeliController::class, 'showPembelibyPembeli']);
    Route::delete('/Pembeli/{id}', [PembeliController::class, 'destroy']);

    //Organisasi
    Route::get('/Organisasi', [OrganisasiController::class, 'index']);
    Route::get('/Organisasi/all', [OrganisasiController::class, 'getData']);
    Route::get('/OrganisasiData', [OrganisasiController::class, 'show']);
    Route::post('/Organisasi', [OrganisasiController::class, 'store']);
    Route::post('/Organisasi/{id}', [OrganisasiController::class, 'update']);
    Route::post('/OrganisasiEditData', [OrganisasiController::class, 'edit']);
    Route::post('/resetPasswordOrganisasi/{id}', [OrganisasiController::class, 'resetPassword']);
    Route::post('/sendResetLinkOrganisasi', [OrganisasiController::class, 'sendResetLink']);
    Route::get('/Organisasi/Organisasi/{id}', [OrganisasiController::class, 'showOrganisasibyOrganisasi']);
    Route::delete('/Organisasi/{id}', [OrganisasiController::class, 'destroy']);

    //Pegawai
    Route::get('/Pegawai', [PegawaiController::class, 'index']);
    Route::get('/Pegawai/all', [PegawaiController::class, 'getData']);
    // Route::get('/Pegawai/Count', [PegawaiController::class, 'countPegawai']);
    Route::get('/PegawaiData', [PegawaiController::class, 'show']);
    Route::post('/Pegawai', [PegawaiController::class, 'store']);
    Route::post('/Pegawai/{id}', [PegawaiController::class, 'update']);
    Route::post('/PegawaiEditData', [PegawaiController::class, 'edit']);
    Route::post('/resetPasswordPegawai/{id}', [PegawaiController::class, 'resetPassword']);
    Route::get('/Pegawai/Pegawai/{id}', [PegawaiController::class, 'showPegawaibyPegawai']);
    Route::delete('/Pegawai/{id}', [PegawaiController::class, 'destroy']);
});
