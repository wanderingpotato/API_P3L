<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pembelian;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenitipanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penitipan_Barang::with('Kategori_Barang');
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $PenitipanBarang = $query->paginate($perPage);

        return response([
            'message' => 'All PenitipanBarang Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }

    public function getBarangWithSearch(Request $request)
    {
        $query = Penitipan_Barang::with('Kategori_Barang');
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        $PenitipanBarang = $query->get();
        return response([
            'message' => 'All PenitipanBarang Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }

    public function getData()
    {
        $data = Penitipan_Barang::with(['Kategori_Barang', 'Gallery'])->get();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }

    public function Cek7Hari()
    {
        $today = Carbon::now()->toDateString();
        $expiredBarang = Penitipan_Barang::whereDate('batas_ambil', '<=', $today)->get();

        if ($expiredBarang->isEmpty()) {
            return response([
                'message' => 'No PenitipanBarang to update',
                'data' => null
            ], 404);
        }
        foreach ($expiredBarang as $barang) {
            $barang->status = 'Untuk Donasi';
            $barang->save();
        }

        return response([
            'message' => 'PenitipanBarang statuses updated to Untuk Donasi',
            'data' => $expiredBarang
        ], 200);
    }

    public function Updaterating(Request $request, $id)
    {
        $PenitipanBarang = Penitipan_Barang::find($id);
        if (is_null($PenitipanBarang)) {
            return response([
                'message' => 'PenitipanBarang Not Found',
                'data' => null
            ], 404);
        }

        $PenitipanBarang->update([
            "rating" => $request->input('rating'),
            "tanggal_rating" => now()
        ]);

        $user = Penitip::find($PenitipanBarang->id_penitip);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        // Panggil update rata-rata rating dari PenitipController
        $penitipController = new \App\Http\Controllers\PenitipController();
        $penitipController->updateRataRataRatingPenitip($user->id_penitip);

        return response([
            'message' => 'Rating updated successfully',
            'data' => $user
        ], 200);
    }

    public function showPenitipanBarangbyPenitip($id)
    {
        $user = Penitip::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $PenitipanBarang = Penitipan_Barang::with(['gallery'])->where('id_penitip', $user->id_penitip)->get();

        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    public function showPenitipanBarangbyHunter($id)
    {
        $user = Pegawai::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $PenitipanBarang = Penitipan_Barang::where('id_pegawai', $user->id_pegawai)->where('status','DiBeli')->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    public function countPenitipanBarangbyHunter($id)
    {
        $user = Pegawai::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $JumlahPenitipanBarang = Penitipan_Barang::where('id_pegawai_hunter', $user->id_pegawai)->where('status','DiBeli')->count();
        return response([
            'message' => 'Jumlah penitipan Barang of ' . $user->name . ' adalah ' . $JumlahPenitipanBarang ,
            'data' => $JumlahPenitipanBarang
        ], 200);
    }

    public function getDataByPenitipId()
    {
        $idUser = Auth::id();
        $user = Penitip::find($idUser);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $PenitipanBarang = Penitipan_Barang::where('id_penitip', $user->id_penitip)->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    public function showPenitipanBarangbyPembeli($id)
    {
        $user = Pembeli::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $pembelianIds = $user->Pembelian()->pluck('id_pembelian');
        $barangIds = Detail_Pembelian::whereIn('id_pembelian', $pembelianIds)->pluck('id_barang');
        $PenitipanBarang = Penitipan_Barang::whereIn('id_barang', $barangIds)->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();
        $storeData['di_perpanjang'] = false;

        // Atur rules validasi dinamis sesuai nilai hunter
        $rules = [
            'id_kategori' => 'required',
            'id_penitip' => 'required',
            'id_pegawai_qc' => 'required|exists:pegawais,id_pegawai',
            'nama_barang' => 'required',
            'diliver_here' => 'required',
            'hunter' => 'required|boolean',
            'status' => 'required',
            'harga_barang' => 'required',
            'rating' => '',
            'tanggal_penitipan' => 'required',
            'tanggal_kadaluarsa' => '',
            'batas_ambil' => '',
            'tanggal_laku' => '',
            'tanggal_rating' => '',
            'garansi' => '',
            'deskripsi' => '',
        ];

        // Validasi hunter dan id_pegawai_hunter
        if (isset($storeData['hunter']) && $storeData['hunter'] == true) {
            $rules['id_pegawai_hunter'] = 'required|exists:pegawais,id_pegawai';
        } else {
            $rules['id_pegawai_hunter'] = 'nullable|exists:pegawais,id_pegawai';
            // Jika hunter false, kosongkan id_pegawai_hunter agar tidak error
            $storeData['id_pegawai_hunter'] = null;
        }

        $validate = Validator::make($storeData, $rules);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $tanggalMasuk = Carbon::parse($storeData['tanggal_penitipan']);
        $tanggalKadaluarsa = $tanggalMasuk->copy()->addDays(30);
        $storeData['tanggal_kadaluarsa'] = $tanggalKadaluarsa->toDateString();

        $batasAmbil = $tanggalKadaluarsa->copy()->addDays(7);
        $storeData['batas_ambil'] = $batasAmbil->toDateString();

        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response(['message' => 'User Not Found'], 403);
        }
        if ($user->id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $lastId = Penitipan_Barang::latest('id_barang')->first();
        $newId = $lastId ? 'PB-' . str_pad((int) substr($lastId->id_barang, 3) + 1, 4, '0', STR_PAD_LEFT) : 'PB-0001';
        $storeData['id_barang'] = $newId;

        $PenitipanBarang = Penitipan_Barang::create($storeData);

        return response([
            'message' => 'Penitipan Barang Added Successfully',
            'data' => $PenitipanBarang,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $PenitipanBarang = Penitipan_Barang::with(['Kategori_Barang'])->find($id);

        if ($PenitipanBarang) {
            return response([
                'message' => 'PenitipanBarang Found',
                'data' => $PenitipanBarang
            ], 200);
        }

        return response([
            'message' => 'PenitipanBarang Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $PenitipanBarang = Penitipan_Barang::find($id);
        if (is_null($PenitipanBarang)) {
            return response([
                'message' => 'PenitipanBarang Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        // Atur rules validasi dinamis sesuai nilai hunter
        $rules = [
            'id_kategori' => 'required',
            'id_penitip' => 'required',
            'id_pegawai_qc' => 'required|exists:pegawais,id_pegawai',
            'nama_barang' => 'required',
            'di_perpanjang' => 'required',
            'diliver_here' => 'required',
            'hunter' => 'required|boolean',
            'status' => 'required',
            'harga_barang' => 'required',
            'rating' => '',
            'tanggal_penitipan' => 'required',
            'tanggal_kadaluarsa' => '',
            'batas_ambil' => '',
            'tanggal_laku' => '',
            'tanggal_rating' => '',
            'garansi' => '',
            'deskripsi' => '',
        ];

        // Validasi hunter dan id_pegawai_hunter
        if (isset($updateData['hunter']) && $updateData['hunter'] == true) {
            $rules['id_pegawai_hunter'] = 'required|exists:pegawais,id_pegawai';
        } else {
            $rules['id_pegawai_hunter'] = 'nullable|exists:pegawais,id_pegawai';
            // Jika hunter false, kosongkan id_pegawai_hunter agar tidak error
            $updateData['id_pegawai_hunter'] = null;
        }

        $validate = Validator::make($updateData, $rules);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $tanggalMasuk = Carbon::parse($updateData['tanggal_penitipan']);
        $tanggalKadaluarsa = $tanggalMasuk->copy()->addDays(30);
        $updateData['tanggal_kadaluarsa'] = $tanggalKadaluarsa->toDateString();

        // Hitung batas_ambil = tanggal_kadaluarsa + 7 hari
        $batasAmbil = $tanggalKadaluarsa->copy()->addDays(7);
        $updateData['batas_ambil'] = $batasAmbil->toDateString();

        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        if ($user->id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        // AUTO PERPANJANGAN
        if (
            isset($updateData['status']) &&
            $updateData['status'] === 'Kadaluarsa' &&
            isset($updateData['di_perpanjang']) &&
            $updateData['di_perpanjang'] == true
        ) {
            $tanggalBaru = Carbon::parse($updateData['tanggal_kadaluarsa'])->addDays(30);
            $updateData['tanggal_kadaluarsa'] = $tanggalBaru->toDateString();
            $updateData['batas_ambil'] = $tanggalBaru->copy()->addDays(7)->toDateString();
            $updateData['status'] = 'DiJual'; // Ganti dengan status aktif kamu jika beda
        }

        $PenitipanBarang->update($updateData);

        return response([
            'message' => 'PenitipanBarang Updated Successfully',
            'data' => $PenitipanBarang,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $PenitipanBarang = Penitipan_Barang::find($id);

        if (is_null($PenitipanBarang)) {
            return response([
                'message' => 'PenitipanBarang Not Found',
                'data' => null
            ], 404);
        }

        if ($PenitipanBarang->delete()) {
            return response([
                'message' => 'PenitipanBarang Deleted Successfully',
                'data' => $PenitipanBarang,
            ], 200);
        }

        return response([
            'message' => 'Delete PenitipanBarang Failed',
            'data' => null,
        ], 400);
    }

    public function laporanStokGudang(Request $request)
    {
        $query = DB::table('penitipan__barangs as b')
            ->join('penitips as p', 'b.id_penitip', '=', 'p.id_penitip')
            ->leftJoin('pegawais as h', 'b.id_pegawai_hunter', '=', 'h.id_pegawai')
            ->select(
                'b.id_barang',
                'b.nama_barang',
                'b.harga_barang',
                'b.tanggal_penitipan',
                'b.di_perpanjang',
                'b.id_pegawai_hunter',
                'p.id_penitip',
                'p.name as nama_penitip',
                'h.name as nama_hunter'
            )
            // Filter hanya barang dengan status 'DiJual'
            ->where('b.status', 'DiJual');

        // Optional: Tambahkan filter pencarian nama barang
        if ($request->has('search') && $request->search != '') {
            $query->where('b.nama_barang', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->query('per_page', 10); // default 10
        $data = $query->paginate($perPage);

        return response([
            'message' => 'Laporan Stok Gudang Retrieved',
            'data' => $data
        ], 200);
    }

    public function laporanPenjualanPerKategori(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Status yang dianggap gagal terjual
        $statusGagalTerjual = ['Untuk Donasi', 'Kadaluarsa', 'DiDonasikan'];

        $data = DB::table('penitipan__barangs as pb')
            ->join('kategori__barangs as kb', 'pb.id_kategori', '=', 'kb.id_kategori')
            ->select(
                'kb.nama_kategori',
                DB::raw("SUM(CASE WHEN pb.status = 'DiBeli' THEN 1 ELSE 0 END) as jumlah_terjual"),
                DB::raw("SUM(CASE WHEN pb.status IN ('Untuk Donasi', 'Kadaluarsa', 'DiDonasikan') THEN 1 ELSE 0 END) as jumlah_gagal_terjual")
            )
            ->whereYear('pb.tanggal_penitipan', $tahun)
            ->groupBy('kb.nama_kategori')
            ->orderBy('kb.nama_kategori')
            ->get();

        return response()->json($data);
    }

    public function laporanBarangMasaTitipHabis(Request $request)
    {
        $today = date('Y-m-d');

        $query = DB::table('penitipan__barangs as b')
            ->join('penitips as p', 'b.id_penitip', '=', 'p.id_penitip')
            ->select(
                'b.id_barang as kode_produk',
                'b.nama_barang as nama_produk',
                'p.id_penitip',
                'p.name as nama_penitip',
                'b.tanggal_penitipan as tanggal_masuk',
                'b.tanggal_kadaluarsa as tanggal_akhir',
                'b.batas_ambil'
            )
            // Filter barang yang masa titipnya sudah habis (tanggal_kadaluarsa < hari ini)
            ->whereDate('b.tanggal_kadaluarsa', '<', $today);

        // Optional: filter pencarian nama produk atau nama penitip
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('b.nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('p.name', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->query('per_page', 10); // default 10 per page
        $data = $query->paginate($perPage);

        return response([
            'message' => 'Laporan Barang Masa Titip Habis Retrieved',
            'data' => $data
        ], 200);
    }
}
