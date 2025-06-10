<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pembelian;
use App\Models\Detail_Pendapatan;
use App\Models\Komisi;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Pembelian;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembelian::with(['detail__pembelians', 'pembeli', 'alamat',]);
        if ($request->has('search') && $request->search != '') {
            $query->where('id_pembelian', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Pembelian = $query->paginate($perPage);


        return response([
            'message' => 'All Pembelian Retrieved',
            'data' => $Pembelian
        ], 200);
    }
    public function getData()
    {
        $data = Pembelian::all();

        return response([
            'message' => 'All kamar Retrieved',
            'data' => $data
        ], 200);
    }
    public function getDataByPembeliId()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Pembelian::where('id_pembeli', $idUser)->with("detail__pembelians")->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }

    public function showKeranjang()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Pembelian::where('id_pembeli', $idUser)->where('status', 'Keranjang')->with(['detail__pembelians.penitip', "detail__pembelians", 'detail__pembelians.gallery'])->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }

    public function getDataWithPembeliAndAlamat()
    {
        $data = Pembelian::with(['pembeli', 'alamat', 'detail__pembelians', 'pegawai'])->get();

        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function showPembelianbyUser($id)
    {
        $user = Pembeli::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Pembelian = Pembelian::with(['detail__pembelians', 'detail__pembelians.gallery'])->where('id_pembeli', $user->id_pembeli)->get();
        return response([
            'message' => 'Pembelian of ' . $user->name . ' Retrieved',
            'data' => $Pembelian
        ], 200);
    }
    public function showPembelianbyKurir($id)
    {
        $user = Pegawai::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Pembelian = Pembelian::with(['detail__pembelians', 'detail__pembelians.gallery'])->where('id_pegawai', $user->id_pegawai)->get();
        return response([
            'message' => 'Pembelian of ' . $user->name . ' Retrieved',
            'data' => $Pembelian
        ], 200);
    }
    public function showPembelianbyId($id)
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Pembelian = Pembelian::with(['detail__pembelians', 'alamat', 'detail__pembelians.gallery'])->where('id_pembelian', $id)->get();
        return response([
            'message' => 'Pembelian of ' . $user->name . ' Retrieved',
            'data' => $Pembelian
        ], 200);
    }
    public function countPembelian()
    {
        $count = Pembelian::count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }

    public function countPembelianByPembeli()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response(['message' => 'User Not Found'], 404);
        }
        $count = Pembelian::where('id_pembeli', $idUser)->count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }

    public function InsertBukti(Request $request, $id)
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $Pembelian = Pembelian::find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'bukti_pembayaran' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('bukti_pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->bukti_pembayaran);
            $updateData['bukti_pembayaran'] = $uploadedImageResponse;
        }
        $Pembelian->update($updateData);

        return response([
            'message' => 'Pembelian Updated Successfully',
            'data' => $Pembelian,
        ], 200);
    }

    public function KonfirmasiPembelian($id)
    {
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
        $Pembelian = Pembelian::with('detail__pembelians')->find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        $pembeli = Pembeli::find($Pembelian->id_pembeli);
        if (is_null($pembeli)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }
        $updateData = [];
        $updateData["status"] = "Selesai";
        $updateData["status_pengiriman"] = "DiSiapkan";
        $updateData["tanggal_lunas"] = Carbon::now()->toDateTimeString();
        $pembeli->increment('poin', $Pembelian->point_yg_didapat);

        $Pembelian->update($updateData);
        foreach ($Pembelian->detail__pembelians as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $UpdateChild['status'] = 'DiBeli';
            $UpdateChild['tanggal_laku'] = $updateData['tanggal_lunas'];

            $Penitipan_Barang->update($UpdateChild);
            if ($Pembelian->status == "Selesai") {
                $storeChildData['id_barang'] = $Penitipan_Barang->id_barang;
                $storeChildData['bonus_penitip'] = 0;
                $storeChildData['komisi_hunter'] = 0;
                $storeChildData['komisi_toko'] = $Penitipan_Barang->harga_barang * (20 / 100);
                $KomisiTemp = $Penitipan_Barang->harga_barang * (20 / 100);
                if ($Penitipan_Barang->hunter == 1) {
                    $storeChildData['id_pegawai'] = $Penitipan_Barang->id_pegawai;
                    $storeChildData['komisi_hunter'] = $KomisiTemp * (5 / 100);
                    $storeChildData['komisi_toko'] = $storeChildData['komisi_toko'] - $storeChildData['komisi_hunter'];
                }
                $storeChildData['id_penitip'] = $Penitipan_Barang->id_penitip;
                if (Carbon::parse($Penitipan_Barang->tanggal_penitipan)->diffInDays(Carbon::parse($Penitipan_Barang->tanggal_laku)) <= 7) {
                    $storeChildData['bonus_penitip'] = $KomisiTemp * (10 / 100);
                    $storeChildData['komisi_toko'] = $storeChildData['komisi_toko'] - $storeChildData['bonus_penitip'];
                }
                $storeChildData['komisi_penitip'] = $Penitipan_Barang->harga_barang - ($storeChildData['komisi_toko'] + $storeChildData['komisi_hunter']);

                $lastId = Komisi::latest('id_komisi')->first();
                $newId = $lastId ? 'K-' . str_pad((int) substr($lastId->id_komisi, 2) + 1, 4, '0', STR_PAD_LEFT) : 'K-0001';
                $storeChildData['id_komisi'] = $newId;

                $storeChildData['tanggal_komisi'] = Carbon::now()->toDateTimeString();
                $validate = Validator::make($storeChildData, [
                    'id_barang' => 'required',
                    'komisi_penitip' => 'required',
                    'komisi_toko' => 'required',
                    'tanggal_komisi' => 'required',
                ]);
                Komisi::create($storeChildData);

                if ($storeChildData['id_penitip'] != null && $Penitipan_Barang->id_penitip != null) {
                    $Penitip = Penitip::where('id_penitip', $Penitipan_Barang->id_penitip)->first();
                    $UpdateDataPenitip['saldo'] = $Penitip->saldo + $storeChildData['komisi_penitip'];
                    $total =  $storeChildData['komisi_penitip'];
                    if ($storeChildData['bonus_penitip'] != 0) {
                        $UpdateDataPenitip['saldo'] = $UpdateDataPenitip['saldo'] +  $storeChildData['bonus_penitip'];
                        $total = $total +  $storeChildData['bonus_penitip'];
                    }
                    $Penitip->update($UpdateDataPenitip);
                    $currentDate = Carbon::now()->startOfMonth();
                    $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->first();
                    if (is_null($DataPenjualan)) {
                        $StoreTambah['id_penitip'] = $Penitipan_Barang->id_penitip;
                        $StoreTambah['total'] = $total;
                        $StoreTambah['month'] = $currentDate->toDateString();
                        $StoreTambah['bonus_pendapatan'] = 0;
                        Detail_Pendapatan::create($StoreTambah);
                    } else {
                        $StoreTambah['id_penitip'] = $Penitipan_Barang->id_penitip;
                        $StoreTambah['total'] = $DataPenjualan->total + $total;
                        $DataPenjualan->update($StoreTambah);
                    }
                }
            }
        }
        return response([
            'message' => 'Pembelian Updated Successfully',
            'data' => $Pembelian,
        ], 200);
    }

    public function tolakPembelian($id)
    {
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
        $Pembelian = Pembelian::with('detail__pembelians')->find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        $pembeli = Pembeli::find($Pembelian->id_pembeli);
        if (is_null($pembeli)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        $pembeli->increment('poin', $Pembelian->point_digunakan);
        $Pembelian->status = 'Batal';
        $Pembelian->save();
        foreach ($Pembelian->detail__pembelians as $detail) {
            $barang = Penitipan_Barang::find($detail->id_barang);
            if ($barang) {
                $barang->status = 'DiJual';
                $barang->save();
            }
        }

        return response([
            'message' => 'Pembelian Updated Successfully',
            'data' => $Pembelian,
        ], 200);
    }
    // public function filterbymonth()
    // {
    //     $data = Pembelian::whereMonth("tanggal_pembelian", 9)->get();

    //     return response([
    //         'message' => 'All JenisKamar Retrieved',
    //         'data' => $data
    //     ], 200);
    // }
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_alamat' => '',
            'id_pegawai' => '',
            'dilivery' => 'required',
            'status' => '',
            'status_pengiriman' => '',
            'point_yg_didapat' => '',
            'point_current' => '',
            'point_digunakan' => 'required',
            'potongan_harga' => '',
            'harga_barang' => '',
            'ongkir' => 'required',
            'batas_waktu' => '',
            'tanggal_pembelian' => '',
            'tanggal_lunas' => '',
            'tanggal_pengiriman-pengambilan' => '',
            'batas_pembeli_ambil_barang' => '',
            'bukti_pembayaran' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $storeData['status'] = 'Proses';
        $storeData['tanggal_lunas'] = '2000-01-01';
        $storeData['tanggal_pembelian'] = Carbon::now()->toDateTimeString();
        $tanggalPembelian = Carbon::parse($storeData['tanggal_pembelian']);
        $batasWaktu = $tanggalPembelian->copy()->addMinute(1);
        $storeData['batas_waktu'] = $tanggalPembelian->addMinute(1)->toDateTimeString();

        $storeData['potongan_harga'] = intdiv($storeData['point_digunakan'], 100) * 10000;

        $lastId = Pembelian::latest('id_pembelian')->first();
        $newId = $lastId ? 'PM-' . str_pad((int) substr($lastId->id_pembelian, 3) + 1, 4, '0', STR_PAD_LEFT) : 'PM-0001';
        $storeData['id_pembelian'] = $newId;
        // dd($newId);
        //Nyari Sesuai User
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $storeData['point_current'] = $user->poin;
        $storeData['id_pembeli'] = $user->id_pembeli;

        $user->decrement('poin', $storeData["point_digunakan"]);
        //Ngitung Total Harga
        $storeData['harga_barang'] = 0;
        $p = 0;
        foreach ($request->Data as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $p += (int)$Penitipan_Barang->harga_barang;
        }
        $storeData['harga_barang'] = $p;

        $poinDasar = floor($p / 10000);
        if ($p > 500000) {
            $poinBonus = $poinDasar * 0.20;
            $totalPoin = $poinDasar + $poinBonus;
        } else {
            $totalPoin = $poinDasar;
        }
        $storeData['point_yg_didapat'] = (int) $totalPoin;

        if ($request->hasFile('bukti_pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('bukti_pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            $storeData['bukti_pembayaran'] = $uploadedImageResponse;
        }

        $Pembelian = Pembelian::create($storeData);
        foreach ($request->Data as $items) {
            $storeChildData = $items;
            $storeChildData['id_pembelian'] = $newId;
            $Penitipan_Barang = Penitipan_Barang::find($items['id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $storeChildData['id_barang'] = $Penitipan_Barang->id_barang;
            $storeChildData['id_penitip'] = $Penitipan_Barang->id_penitip;
            $validate = Validator::make($storeChildData, [
                'id_pembelian' => 'required',
                'id_barang' => 'required',
                'id_penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }

            Detail_Pembelian::create($storeChildData);
            $UpdateChild['status'] = 'DiBeli';
            $Penitipan_Barang->update($UpdateChild);
        }
        return response([
            'message' => 'Pembelian Added Successfully',
            'data' => $Pembelian,
        ], 200);
    }

    public function storeDashboard(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_pembeli' => 'required',
            'id_alamat' => '',
            'id_pegawai' => '',
            'dilivery' => 'required',
            'status' => 'required',
            'status_pengiriman' => 'required',
            'point_yg_didapat' => '',
            'point_current' => 'required',
            'point_digunakan' => 'required',
            'potongan_harga' => '',
            'harga_barang' => '',
            'ongkir' => 'required',
            'batas_waktu' => '', // setting otomatis?
            'tanggal_pembelian' => 'required',
            'tanggal_lunas' => '',
            'tanggal_pengiriman-pengambilan' => '',
            'batas_pembeli_ambil_barang' => '',
            'bukti_pembayaran' => '',
        ]);
        $storeData['tanggal_lunas'] = '2000-01-01';

        $tanggalPembelian = Carbon::parse($storeData['tanggal_pembelian']);
        $batasWaktu = $tanggalPembelian->copy()->addMinute(1);
        $storeData['batas_waktu'] = $tanggalPembelian->addMinute(1)->toDateString();

        $storeData['tanggal_pengiriman-pengambilan'] = '2000-01-01';
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Pembelian::latest('id_pembelian')->first();
        $newId = $lastId ? 'PM-' . str_pad((int) substr($lastId->id_pembelian, 3) + 1, 4, '0', STR_PAD_LEFT) : 'PM-0001';
        $storeData['id_pembelian'] = $newId;

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
        $storeData['potongan_harga'] = intdiv($storeData['point_digunakan'], 100) * 10000;


        $storeChildData['id_barang'] = $storeData['id_barang'];
        $storeChildData['id_pembelian'] = $newId;
        $Penitipan_Barang = Penitipan_Barang::find($storeChildData['id_barang']);
        if (is_null($Penitipan_Barang)) {
            return response([
                'message' => 'Barber Not found',
            ], 404);
        }
        $storeChildData['id_penitip'] = $Penitipan_Barang->id_penitip;
        $storeData['harga_barang'] = $Penitipan_Barang->harga_barang;
        $Pembelian = Pembelian::create($storeData);
        $validate = Validator::make($storeChildData, [
            'id_pembelian' => 'required',
            'id_barang' => 'required',
            'id_penitip' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        Detail_Pembelian::create($storeChildData);

        return response([
            'message' => 'Pembelian Added Successfully',
            'data' => $Pembelian,
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function addToKeranjang(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_pembelian' => '',
            'id_barang' => 'required',
            'id_penitip' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $Pembelian = Pembelian::where('status', 'Keranjang')->first();
        $Penitipan_Barang = Penitipan_Barang::find($storeData['id_barang']);
        $storeData['id_pembelian'] = $Pembelian->id_pembelian;
        $storeData['id_penitip'] = $Penitipan_Barang->id_penitip;

        $DetailPembelian = Detail_Pembelian::create($storeData);
        return response([
            'message' => 'DetailPembelian Added Successfully',
            'data' => $DetailPembelian,
        ], 200);
    }

    public function removeFromKeranjang($id)
    {
        $Pembelian = Pembelian::where('status', 'keranjang')->first();

        if (!$Pembelian) {
            return response([
                'message' => 'Keranjang Not Found',
                'data' => null
            ], 404);
        }

        $deleted = Detail_Pembelian::where("id_pembelian", $Pembelian->id_pembelian)
            ->where('id_barang', $id)
            ->delete();

        if ($deleted) {
            return response([
                'message' => 'Pembelian Deleted Successfully',
                'data' => $Pembelian,
            ], 200);
        }

        return response([
            'message' => 'Delete Pembelian Failed',
            'data' => null,
        ], 400);
    }

    public function checkMinutePembayaran()
    {
        $now = Carbon::now();
        // \Log::info('Current time: ' . $now);

        $expiredRecords = Pembelian::where('batas_waktu', '<=', $now)
            ->where('status', 'Proses')->get();


        // \Log::info('Expired Records Count: ' . $expiredRecords->count());

        foreach ($expiredRecords as $record) {
            $user = Pembeli::find($record->id_pembeli);
            $user->increment('poin', $record->point_digunakan);
            $record->status = 'Batal';
            $record->save();
        }
    }

    public function show(string $id)
    {
        $Pembelian = Pembelian::find($id);

        if ($Pembelian) {
            return response([
                'message' => 'Pembelian Found',
                'data' => $Pembelian
            ], 200);
        }

        return response([
            'message' => 'Pembelian Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Pembelian = Pembelian::find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_alamat' => '',
            'id_pegawai' => '',
            'dilivery' => 'required',
            'status' => '',
            'status_pengiriman' => '',
            'point_yg_didapat' => '',
            'point_current' => '',
            'point_digunakan' => 'required',
            'potongan_harga' => '',
            'harga_barang' => '',
            'ongkir' => 'required',
            'batas_waktu' => '',
            'tanggal_pembelian' => '',
            'tanggal_lunas' => '',
            'tanggal_pengiriman-pengambilan' => '',
            'batas_pembeli_ambil_barang' => '',
            'bukti_pembayaran' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $updateData['status'] = 'Proses';
        $updateData['tanggal_lunas'] = '2000-01-01';
        $updateData['tanggal_pembelian'] = Carbon::now()->toDateTimeString();
        $tanggalPembelian = Carbon::parse($updateData['tanggal_pembelian']);
        $batasWaktu = $tanggalPembelian->copy()->addMinute(1);
        $updateData['batas_waktu'] = $tanggalPembelian->addMinute(1)->toDateTimeString();


        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $updateData['point_current'] = $user->poin;
        $updateData['id_pembeli'] = $user->id_pembeli;

        $user->increment('poin', $Pembelian->point_digunakan);
        $user->decrement('poin', $updateData["point_digunakan"]);
        //Ngitung Total Harga


        $updateData['harga_barang'] = 0;
        $p = 0;
        Detail_Pembelian::where('id_pembelian', $id)->delete();
        foreach ($request->Data as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $p += (int)$Penitipan_Barang->harga_barang;
            $storeChildData = $item;
            $storeChildData['id_pembelian'] = $id;
            $storeChildData['id_penitip'] = $Penitipan_Barang->id_penitip;
            $validate = Validator::make($storeChildData, [
                'id_pembelian' => 'required',
                'id_barang' => 'required',
                'id_penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            Detail_Pembelian::create($storeChildData);
        }
        $updateData['harga_barang'] = $p;

        $poinDasar = floor($p / 10000);
        if ($p > 500000) {
            $poinBonus = $poinDasar * 0.20;
            $totalPoin = $poinDasar + $poinBonus;
        } else {
            $totalPoin = $poinDasar;
        }
        $updateData['point_yg_didapat'] = (int) $totalPoin;

        $updateData['potongan_harga'] = intdiv($updateData['point_digunakan'], 100) * 10000;

        if ($request->hasFile('bukti_pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('bukti_pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->bukti_pembayaran);
            $updateData['bukti_pembayaran'] = $uploadedImageResponse;
        }

        $Pembelian->update($updateData);

        return response([
            'message' => 'Pembelian Updated Successfully',
            'data' => $Pembelian,
        ], 200);
    }


    public function updateDashboard(Request $request, string $id)
    {
        $Pembelian = Pembelian::find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_pembeli' => 'required',
            'id_alamat' => '',
            'id_pegawai' => '',
            'dilivery' => 'required',
            'status' => 'required',
            'status_pengiriman' => 'required',
            'point_yg_didapat' => '',
            'point_current' => '',
            'point_digunakan' => 'required',
            'potongan_harga' => '',
            'harga_barang' => '',
            'ongkir' => 'required',
            'batas_waktu' => 'required',
            'tanggal_pembelian' => 'required',
            'tanggal_lunas' => '',
            'tanggal_pengiriman-pengambilan' => '',
            'batas_pembeli_ambil_barang' => '',
            'bukti_pembayaran' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }

        if ($request->has('id_barang') && $request->id_barang != '') {
            Detail_Pembelian::where('id_pembelian', $id)->delete();
            $storeChildData['id_barang'] = $updateData['id_barang'];
            $storeChildData['id_pembelian'] = $id;
            $Penitipan_Barang = Penitipan_Barang::find($storeChildData['id_barang']);
            $updateData['harga_barang'] = $Penitipan_Barang->harga_barang;
            $updateData['potongan_harga'] = intdiv($updateData['point_digunakan'], 100) * 10000;
            $storeChildData['id_penitip'] = $Penitipan_Barang->id_penitip;
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $validate = Validator::make($storeChildData, [
                'id_pembelian' => 'required',
                'id_barang' => 'required',
                'id_penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            Detail_Pembelian::create($storeChildData);
        }
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('bukti_pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->bukti_pembayaran);
            $updateData['bukti_pembayaran'] = $uploadedImageResponse;
        }

        if ($updateData['status_pengiriman'] === "Sampai") {
            $updateData['status'] = "Selesai";
        }

        if ($updateData['status_pengiriman'] === "Sudah Diambil") {
            $updateData['status'] = "Selesai";
        }

        if ($updateData['tanggal_pengiriman-pengambilan']) {
            $updateData['batas_pembeli_ambil_barang'] = Carbon::parse($updateData['tanggal_pengiriman-pengambilan'])->copy()->addDays(2)->toDateString();
        }

        $Pembelian->update($updateData);

        return response([
            'message' => 'Pembelian Updated Successfully',
            'data' => $Pembelian,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Pembelian = Pembelian::find($id);

        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        if ($Pembelian->delete()) {
            return response([
                'message' => 'Pembelian Deleted Successfully',
                'data' => $Pembelian,
            ], 200);
        }

        return response([
            'message' => 'Delete Pembelian Failed',
            'data' => null,
        ], 400);
    }


    public function laporanPenjualanBulanan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Ambil total penjualan per bulan dari pembelians
        $penjualanPerBulan = DB::table('pembelians')
            ->select(
                DB::raw('MONTH(tanggal_pembelian) as bulan'),
                DB::raw('SUM(harga_barang) as total_penjualan')
            )
            ->whereYear('tanggal_pembelian', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal_pembelian)'))
            ->orderBy('bulan')
            ->get();

        // Ambil jumlah barang terjual per bulan dari detail__pembelians join pembelians
        $jumlahBarangPerBulan = DB::table('detail__pembelians')
            ->join('pembelians', 'detail__pembelians.id_pembelian', '=', 'pembelians.id_pembelian')
            ->select(
                DB::raw('MONTH(pembelians.tanggal_pembelian) as bulan'),
                DB::raw('COUNT(detail__pembelians.id_barang) as jumlah_barang')
            )
            ->whereYear('pembelians.tanggal_pembelian', $tahun)
            ->groupBy(DB::raw('MONTH(pembelians.tanggal_pembelian)'))
            ->orderBy('bulan')
            ->get();

        $result = [];

        for ($i = 1; $i <= 12; $i++) {
            $penjualan = $penjualanPerBulan->firstWhere('bulan', $i);
            $jumlahBarang = $jumlahBarangPerBulan->firstWhere('bulan', $i);

            $result[] = [
                'bulan' => $this->namaBulanIndonesia($i),
                'jumlah' => $jumlahBarang ? (int) $jumlahBarang->jumlah_barang : 0,
                'penjualan' => $penjualan ? (int) $penjualan->total_penjualan : 0,
            ];
        }

        return response()->json($result);
    }

    private function namaBulanIndonesia($bulan)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $namaBulan[$bulan] ?? '';
    }

    public function setStatus($id){
        $Pembelian = Pembelian::find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }
        $Pembelian->status_pengiriman="Sampai";
        $Pembelian->save();
        return response([
            'message' => 'Pembelian Sampai Successfully',
            'data' => $Pembelian,
        ], 200);
    }
}
