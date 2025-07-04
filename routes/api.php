<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\DetailDonasiController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\DetailPendapatanController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\GalleryController;
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

// Route::get('/filterbymonth', [PembelianController::class, 'filterbymonth']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/EditUserPenitip', [PenitipController::class, 'edit']);
  Route::post('/logoutPenitip', [PenitipController::class, 'logout']);
  
  Route::post('/EditUserPembeli', [PembeliController::class, 'edit']);
  Route::post('/logoutPembeli', [PembeliController::class, 'logout']);
  
  Route::post('/EditOrganisasi', [OrganisasiController::class, 'edit']);
  Route::post('/logoutOrganisasi', [OrganisasiController::class, 'logout']);
  
  Route::post('/EditPegawai', [PegawaiController::class, 'edit']);
  Route::post('/logoutPegawai', [PegawaiController::class, 'logout']);
  
  //Penitipan Barang
  Route::post('/PenitipanBarang', [PenitipanBarangController::class, 'store']);
  Route::post('/PenitipanBarang/Cek7Hari', [PenitipanBarangController::class, 'Cek7Hari']);
  Route::get('/PenitipanBarang/user', [PenitipanBarangController::class, 'getDataByPenitipId']);
  // Route::post('/PenitipanBarang/Dashboard', [PenitipanBarangController::class, 'storeDashboard']); -> ini knp ada ini
  // Route::get('/PenitipanBarang/User/Count', [PenitipanBarangController::class, 'countPenitipanBarangByUser']);
  Route::post('/PenitipanBarang/{id}', [PenitipanBarangController::class, 'update']);
  // Route::post('/PenitipanBarang/Dashboard/{id}', [PenitipanBarangController::class, 'updateDashboard']); -> ini juga knp
  Route::get('/PenitipanBarang/Penitip/{id}', [PenitipanBarangController::class, 'showPenitipanBarangbyPenitip']);
  Route::get('/PenitipanBarang/Hunter/{id}', [PenitipanBarangController::class, 'showPenitipanBarangbyHunter']);
  Route::get('/PenitipanBarang/Pembeli/{id}', [PenitipanBarangController::class, 'showPenitipanBarangbyPembeli']);
  Route::get('/laporan/stok-gudang', [PenitipanBarangController::class, 'laporanStokGudang']);
  Route::get('/laporan/penjualan-per-kategori', [PenitipanBarangController::class, 'laporanPenjualanPerKategori']);
  Route::get('/laporan/barang-masa-titip-habis', [PenitipanBarangController::class, 'laporanBarangMasaTitipHabis']);
  Route::post('/PenitipanBarang/UpdateRating/{id}', [PenitipanBarangController::class, 'UpdateRating']);
  Route::delete('/PenitipanBarang/{id}', [PenitipanBarangController::class, 'destroy']);
  
  //Donasi
  Route::post('/Donasi', [DonasiController::class, 'store']);
  Route::post('/Donasi/Dashboard', [DonasiController::class, 'storeDashboard']);
  Route::post('/Donasi/{id}', [DonasiController::class, 'update']);
  Route::post('/Donasi/Korfirm/{id}', [DonasiController::class, 'UpdateKorfirmasi']);
  Route::post('/Donasi/Details/{id}', [DonasiController::class, 'UpdateDetailDonasi']);
  Route::post('/Donasi/Dashboard/{id}', [DonasiController::class, 'updateDashboard']);
  Route::get('/Donasi/Organisasi/{id}', [DonasiController::class, 'getDataByOrganisasiId']);
  Route::get('/Donasi/Organisasi/Count/{id}', [DonasiController::class, 'countDonasiByOrganisasi']);
  Route::get('/Donasi/Barang/{id}', [DonasiController::class, 'getDataWithPenitipanBarangById']);
  Route::get('/Donasi/Organisasi/Barang/{id}', [DonasiController::class, 'getDataWithPenitipanBarangByIdOrganisasi']);
  Route::get('/laporan/rekam-request-donasi', [DonasiController::class, 'laporanRekamRequestDonasi']);
  Route::delete('/Donasi/{id}', [DonasiController::class, 'destroy']);
  
  //KategoriBarang
  Route::post('/KategoriBarang', [KategoriBarangController::class, 'store']);
  Route::post('/KategoriBarang/{id}', [KategoriBarangController::class, 'update']);
  Route::get('/KategoriBarang/PenitipanBarang/', [KategoriBarangController::class, 'showKategoriBarangWithPenitipanBarang']);
  Route::get('/KategoriBarang/PenitipanBarang/{id}', [KategoriBarangController::class, 'showKategoriBarangWithPenitipanBarangByKategori_BarangId']);
  Route::delete('/KategoriBarang/{id}', [KategoriBarangController::class, 'destroy']);
  
  //Jabatan
  Route::post('/Jabatan', [JabatanController::class, 'store']);
  Route::get('/Jabatan/Pegawai', [JabatanController::class, 'showJabatanWithPegawai']);
  Route::post('/Jabatan/{id}', [JabatanController::class, 'update']);
  Route::get('/Jabatan/Pegawai/{id}', [JabatanController::class, 'showJabatanWithPegawaiByJabatanId']);
  Route::delete('/Jabatan/{id}', [JabatanController::class, 'destroy']);
  
  //Alamat
  Route::post('/Alamat', [AlamatController::class, 'store']);
  Route::post('/Alamat/Pembeli', [AlamatController::class, 'AddAlamatPembeli']);
  Route::get('/Alamat/Pembeli/get', [AlamatController::class, 'AlamatPembeliAuth']);
  Route::post('/Alamat/{id}', [AlamatController::class, 'update']);
  Route::post('/Alamat/Pembeli/{id}', [AlamatController::class, 'EditAlamatPembeli']);
  Route::post('/Alamat/Default/{id}', [AlamatController::class, 'SetDefaultAlamatPembeli']);
  Route::get('/Alamat/Pembeli/{id}', [AlamatController::class, 'showAlamatbyPembeli']);
  Route::delete('/Alamat/{id}', [AlamatController::class, 'destroy']);
  
  //DetailDonasi
  Route::post('/DetailDonasi', [DetailDonasiController::class, 'store']);
  Route::post('/DetailDonasi/{id}', [DetailDonasiController::class, 'update']);
  Route::get('/DetailDonasi/Donasi/{id}', [DetailDonasiController::class, 'showDetailDonasibyDonasi']);
  Route::get('/laporan/donasi-barang', [DetailDonasiController::class, 'laporanDonasiBarang']);
  Route::delete('/DetailDonasi/{id}', [DetailDonasiController::class, 'destroy']);
  
  //DetailPendapatan
  Route::post('/DetailPendapatan', [DetailPendapatanController::class, 'store']);
  Route::post('/setTopSeller', [DetailPendapatanController::class, 'setTopSeller']);
  Route::post('/DetailPendapatan/{id}', [DetailPendapatanController::class, 'update']);
  Route::get('/DetailPendapatan/Penitip/{id}', [DetailPendapatanController::class, 'showDetailPendapatanbyPenitip']);
  Route::delete('/DetailPendapatan/{id}', [DetailPendapatanController::class, 'destroy']);
  
  //DetailPembelian
  Route::post('/DetailPembelian', [DetailPembelianController::class, 'store']);
  Route::get('/DetailPembelian/count', [DetailPembelianController::class, 'countCart']);
  Route::post('/DetailPembelian/{id}', [DetailPembelianController::class, 'update']);
  Route::get('/DetailPembelian/Pembelian/{id}', [DetailPembelianController::class, 'showDetailPembelianbyPembelian']);
  Route::delete('/DetailPembelian/{id}', [DetailPembelianController::class, 'destroy']);
  
  //Komisi
  Route::post('/Komisi', [KomisiController::class, 'store']);
  Route::post('/Komisi/{id}', [KomisiController::class, 'update']);
  Route::get('/Komisi/Pegawai/{id}', [KomisiController::class, 'showKomisibyPegawai']);
  Route::get('/Komisi/Penitip/{id}', [KomisiController::class, 'showKomisibyPenitip']);
  Route::delete('/Komisi/{id}', [KomisiController::class, 'destroy']);
  
  //Gallery
  Route::post('/Gallery', [GalleryController::class, 'store']);
  Route::post('/Gallery/{id}', [GalleryController::class, 'update']);
  Route::delete('/Gallery/{id}', [GalleryController::class, 'destroy']);
  
  //Merchandise
  Route::get('/Merchandise', [MerchandiseController::class, 'index']);
  Route::post('/Merchandise', [MerchandiseController::class, 'store']);
  Route::post('/Merchandise/{id}', [MerchandiseController::class, 'update']);
  Route::get('/Merchandise/Penitip/{id}', [MerchandiseController::class, 'showMerchandisebyPenitip']); // ini masi aneh harusnya aku dari kalim merch
  Route::get('/Merchandise/Pembeli/{id}', [MerchandiseController::class, 'showMerchandisebyPembeli']); // ini masi aneh harusnya aku dari kalim merch
  Route::delete('/Merchandise/{id}', [MerchandiseController::class, 'destroy']);
  
  //KlaimMerchandise
  Route::post('/KlaimMerchandise', [KlaimMerchandiseController::class, 'store']);
  Route::post('/KlaimMerchandise/Dashboard', [KlaimMerchandiseController::class, 'storeDashboard']);
  Route::get('/KlaimMerchandise/Penitip/', [KlaimMerchandiseController::class, 'getDataByPenitipId']);
  Route::get('/KlaimMerchandise/Pembeli/', [KlaimMerchandiseController::class, 'getDataByPembeliId']);
  Route::get('/KlaimMerchandise/Count/Penitip/', [KlaimMerchandiseController::class, 'countKlaimByPenitip']);
  Route::get('/KlaimMerchandise/Count/Pembeli/', [KlaimMerchandiseController::class, 'countKlaimByPembeli']);
  Route::post('/KlaimMerchandise/{id}', [KlaimMerchandiseController::class, 'update']);
  Route::post('/KlaimMerchandise/Dashboard/{id}', [KlaimMerchandiseController::class, 'updateDashboard']);
  Route::post('/KlaimMerchandise/Status/{id}', [KlaimMerchandiseController::class, 'UpdateStatus']);
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
  
  //Pembelian
  Route::post('/Pembelian', [PembelianController::class, 'store']);
  Route::post('/Keranjang', [PembelianController::class, 'addToKeranjang']);
  Route::get('/Pembelian/Keranjang', [PembelianController::class, 'showKeranjang']);
  Route::post('/Pembelian/Dashboard', [PembelianController::class, 'storeDashboard']);
  Route::get('/Pembelian/Pembeli', [PembelianController::class, 'getDataByPembeliId']);
  Route::get('/Pembelian/Admin', [PembelianController::class, 'getDataWithPembeliAndAlamat']);
  Route::get('/Pembelian/Pembeli/Count', [PembelianController::class, 'countPembelianByPembeli']);
  Route::get('/laporan/penjualan-bulanan', [PembelianController::class, 'laporanPenjualanBulanan']);
  Route::get('/Pembelian/{id}', [PembelianController::class, 'showPembelianbyId']);
  Route::post('/Pembelian/Batal/{id}', [PembelianController::class, 'batalkanPembelian']);
  Route::get('/Pembelian/Kurir/{id}', [PembelianController::class, 'showPembelianbyKurir']);
  Route::get('/Pembelian/Pembeli/{id}', [PembelianController::class, 'showPembelianbyUser']);
  Route::post('/Pembelian/{id}', [PembelianController::class, 'update']);
  Route::post('/Pembelian/Pengiriman/{id}', [PembelianController::class, 'setStatus']);
  Route::post('/Pembelian/Bukti/{id}', [PembelianController::class, 'InsertBukti']);
  Route::post('/Pembelian/Konfirm/{id}', [PembelianController::class, 'KonfirmasiPembelian']);
  Route::post('/Pembelian/Tolak/{id}', [PembelianController::class, 'tolakPembelian']);
  Route::post('/Pembelian/Dashboard/{id}', [PembelianController::class, 'updateDashboard']);
  Route::delete('/Keranjang/{id}', [PembelianController::class, 'removeFromKeranjang']);
  Route::delete('/Pembelian/{id}', [PembelianController::class, 'destroy']);;
  //Penitip
  Route::post('/Penitip', [PenitipController::class, 'store']);
  Route::post('/PenitipEditData', [PenitipController::class, 'edit']);
  Route::post('/sendResetLinkPenitip', [PenitipController::class, 'sendResetLink']);
  Route::get('/Penitip', [PenitipController::class, 'index']);
  Route::get('/Penitip/all', [PenitipController::class, 'getData']);
  Route::get('/Penitip/Count', [PenitipController::class, 'countPenitip']);
  Route::get('/PenitipData', [PenitipController::class, 'show']);
  // Route::get('/Penitip/Penitip/{id}', [PenitipController::class, 'showPenitipbyPenitip']); -> ini ga ada fuctionnya
  Route::post('/Penitip/{id}', [PenitipController::class, 'update']);
  Route::post('/Penitip/Foto/{id}', [PenitipController::class, 'editFoto']);
  Route::delete('/Penitip/{id}', [PenitipController::class, 'destroy']);
  Route::post('/resetPasswordPenitip/{id}', [PenitipController::class, 'resetPassword']);
  Route::get('/Penitip/{id}', [PenitipController::class, 'showById']);
  Route::get('/laporan/laporan-penitip', [PenitipController::class, 'laporanPenitip']);

  //Pembeli
  Route::post('/Pembeli', [PembeliController::class, 'store']);
  Route::post('/PembeliEditData', [PembeliController::class, 'edit']);
  Route::post('/sendResetLinkPembeli', [PembeliController::class, 'sendResetLink']);
  Route::get('/Pembeli', [PembeliController::class, 'index']);
  Route::get('/Pembeli/poin', [PembeliController::class, 'poinUser']);
  Route::get('/Pembeli/all', [PembeliController::class, 'getData']);
  Route::get('/Pembeli/Count', [PembeliController::class, 'countPembeli']);
  Route::get('/PembeliData', [PembeliController::class, 'show']);
  // Route::get('/Pembeli/Pembeli/{id}', [PembeliController::class, 'showPembelibyPembeli']); -> ini juga
  Route::post('/Pembeli/{id}', [PembeliController::class, 'update']);
  Route::post('/Pembeli/Foto/{id}', [PembeliController::class, 'editFoto']);
  Route::delete('/Pembeli/{id}', [PembeliController::class, 'destroy']);
  Route::post('/resetPasswordPembeli/{id}', [PembeliController::class, 'resetPassword']);
  
  //Organisasi
  Route::post('/Organisasi', [OrganisasiController::class, 'store']);
  Route::post('/OrganisasiEditData', [OrganisasiController::class, 'edit']);
  Route::post('/sendResetLinkOrganisasi', [OrganisasiController::class, 'sendResetLink']);
  Route::get('/Organisasi', [OrganisasiController::class, 'index']);
  Route::get('/Organisasi/all', [OrganisasiController::class, 'getData']);
  Route::get('/Organisasi/Count', [OrganisasiController::class, 'countOrganisasi']);
  Route::get('/OrganisasiData', [OrganisasiController::class, 'show']);
  // Route::get('/Organisasi/Organisasi/{id}', [OrganisasiController::class, 'showOrganisasibyOrganisasi']); -> ini lagi
  Route::post('/Organisasi/{id}', [OrganisasiController::class, 'update']);
  Route::post('/Organisasi/Foto/{id}', [OrganisasiController::class, 'editFoto']);
  Route::delete('/Organisasi/{id}', [OrganisasiController::class, 'destroy']);
  Route::post('/resetPasswordOrganisasi/{id}', [OrganisasiController::class, 'resetPassword']);

  //Pegawai
  Route::post('/Pegawai', [PegawaiController::class, 'store']);
  Route::post('/PegawaiEditData', [PegawaiController::class, 'edit']);
  Route::get('/Pegawai', [PegawaiController::class, 'index']);
  Route::get('/Pegawai/all', [PegawaiController::class, 'getData']);
  Route::get('/Pegawai/Count', [PegawaiController::class, 'countPegawai']);
  Route::get('/PegawaiData', [PegawaiController::class, 'show']);
  // Route::get('/Pegawai/Pegawai/{id}', [PegawaiController::class, 'showPegawaibyPegawai']); -> ini juga lagi
  Route::post('/Pegawai/{id}', [PegawaiController::class, 'update']);
  Route::post('/Pegawai/Foto/{id}', [PegawaiController::class, 'editFoto']);
  Route::delete('/Pegawai/{id}', [PegawaiController::class, 'destroy']);
  Route::post('/resetPasswordPegawai/{id}', [PegawaiController::class, 'resetPassword']);
});
// Route Yang Ga Perlu Login
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
Route::get('/PenitipanBarang/Perpanjang/{id}', [PenitipanBarangController::class, 'getDataSudahDiperpanjang']);
Route::get('/PenitipanBarang/all', [PenitipanBarangController::class, 'getData']);
// Route::get('/PenitipanBarang/Count', [PenitipanBarangController::class, 'countPenitipanBarang']); -> ternyata udh ilang
Route::get('/PenitipanBarang/search', [PenitipanBarangController::class, 'getBarangWithSearch']);
Route::get('/PenitipanBarang/{id}', [PenitipanBarangController::class, 'show']);

Route::get('/Gallery', [GalleryController::class, 'index']);
Route::get('/Gallery/all', [GalleryController::class, 'getData']);
Route::get('/Gallery/{id}', [GalleryController::class, 'show']);
Route::get('/Gallery/Barang/{id}', [GalleryController::class, 'getDataByBarangId']);

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
Route::get('/laporan/laporan-komisi', [KomisiController::class, 'laporanKomisi']);

Route::get('/KlaimMerchandise', [KlaimMerchandiseController::class, 'index']);
Route::get('/KlaimMerchandise/all', [KlaimMerchandiseController::class, 'getData']);
Route::get('/KlaimMerchandise/Count', [KlaimMerchandiseController::class, 'countKlaim']);
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
Route::get('/Donasi/Barang', [DonasiController::class, 'getDataWithPenitipanBarang']); //Ga usah Di pake

Route::get('/Pembelian', [PembelianController::class, 'index']);
Route::get('/Pembelian/all', [PembelianController::class, 'getData']);
Route::get('/Pembelian/Count', [PembelianController::class, 'countPembelian']);
Route::get('/Pembelian/Show/{id}', [PembelianController::class, 'show']);

Route::get('/PenitipanBarang/Hunter/count/{id}', [PenitipanBarangController::class, 'countPenitipanBarangbyHunter']);
//why tf berhasil kalo di luar login njir
Route::get('/Komisi/Pegawai/count/{id}', [KomisiController::class, 'countKomisibyPegawai']);
//ini sama aja asu