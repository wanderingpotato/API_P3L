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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembelian::with('detail__pembelians');
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
            'message' => 'All JenisKamar Retrieved',
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
        $data = Pembelian::where('id_users', $idUser)->get();
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
        $Pembelian = Pembelian::with('detail__pembelians')->where('id_pembeli', $user->id_pembeli)->get();
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
        $Pembelian = Pembelian::with(['detail__pembelians', 'alamat'])->where('id_pembelian', $id)->get();
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
    
    public function countPembelianByUser()
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
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('bukti_pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->bukti_pembayaran);
            $updateData['bukti_pembayaran'] = $uploadedImageResponse;
        }
        $Pembelian->update($updateData);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $Pembelian->update($updateData);
    }

    public function KonfirmasiPembelian(Request $request, $id)
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
        $Pembelian = Pembelian::find($id);
        if (is_null($Pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'status' => 'required',
            'status_pengiriman' => 'required',
            'tanggal_lunas' => 'required',
            'tanggal_pengiriman-pengambilan' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $Pembelian->update($updateData);
        foreach ($request->Data as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $UpdateChild['status'] = 'DiBeli';
            $UpdateChild['tanggal_laku'] = $updateData['tanggal_lunas'];
            $validate = Validator::make($UpdateChild, [
                'tanggal_laku' => 'required',
                'status' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            $Penitipan_Barang->update($UpdateChild);
            if ($Pembelian->status == "Selesai") {
                $storeChildData['id_barang'] = $Penitipan_Barang->id_barang;
                $storeChildData['bonus_penitip'] = 0;
                $storeChildData['komisi_hunter'] = 0;
                $storeChildData['komisi_toko'] = $Penitipan_Barang->harga_barang * (20 / 100);
                $KomisiTemp = $Penitipan_Barang->harga_barang * (20 / 100);
                if ($Penitipan_Barang->Hunter == 1) {
                    $storeChildData['id_pegawai'] = $Penitipan_Barang->id_pegawai;
                    $storeChildData['komisi_hunter'] = $KomisiTemp * (5 / 100);
                    $storeChildData['komisi_toko'] = $storeChildData['komisi_toko'] - $storeChildData['komisi_hunter'];
                }
                if (Carbon::parse($Penitipan_Barang->Tanggal_penitipan)->diffInDays(Carbon::parse($Penitipan_Barang->tanggal_laku)) <= 7) {
                    $storeChildData['id_penitip'] = $Penitipan_Barang->id_penitip;
                    $storeChildData['bonus_penitip'] = $KomisiTemp * (10 / 100);
                    $storeChildData['komisi_toko'] = $storeChildData['komisi_toko'] - $storeChildData['bonus_penitip'];
                }
                $storeChildData['komisi_penitip'] = $Penitipan_Barang->harga_barang - ($storeChildData['komisi_toko'] + $storeChildData['komisi_hunter']);

                $lastId = Komisi::latest('id_komisi')->first();
                $newId = $lastId ? 'K-' . str_pad((int) substr($lastId->id_komisi, 2) + 1, 4, '0', STR_PAD_LEFT) : 'K-0001';
                $storeChildData['id_komisi'] = $newId;

                $storeChildData['tanggal_komisi'] = $request->tanggal_komisi;
                $validate = Validator::make($storeChildData, [
                    'id_barang' => 'required',
                    'komisi_penitip' => 'required',
                    'komisi_toko' => 'required',
                    'tanggal_komisi' => 'required',
                ]);
                Komisi::create($storeChildData);

                if ($storeChildData['id_penitip'] != null && $Penitipan_Barang->id_penitip != null) {
                    $Penitip = Penitip::where('id_penitip', $Penitipan_Barang->id_penitip)->get();
                    $UpdateDataPenitip['saldo'] = $Penitip->saldo + $storeChildData['komisi_penitip'];
                    $total =  $storeChildData['komisi_penitip'];
                    if ($storeChildData['bonus_penitip'] != 0) {
                        $UpdateDataPenitip['saldo'] = $UpdateDataPenitip['saldo'] +  $storeChildData['bonus_penitip'];
                        $total = $total +  $storeChildData['bonus_penitip'];
                    }
                    $Penitip->update($UpdateDataPenitip);
                    $currentDate = Carbon::now();
                    $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->get();
                    if (is_null($DataPenjualan)) {
                        $StoreTambah['id_penitip'] = $request->id_penitip;
                        $StoreTambah['total'] = $total;
                        $StoreTambah['month'] = $currentDate->toDateString();
                        $StoreTambah['bonus_pendapatan'] = 0;
                        Detail_Pendapatan::create($StoreTambah);
                    } else {
                        $StoreTambah['id_penitip'] = $request->id_penitip;
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
            'id_alamat' => 'required',
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
            'bukti_pembayaran' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $storeData['tanggal_lunas'] = '2000-01-01';
        $storeData['tanggal_pengiriman-pengambilan'] = '2000-01-01';
        $storeData['potongan_harga'] = intdiv($storeData['point_digunakan'], 100) * 10000;

        $lastId = Pembelian::latest('id_pembelian')->first();
        $newId = $lastId ? 'PM-' . str_pad((int) substr($lastId->id_pembelian, 3) + 1, 4, '0', STR_PAD_LEFT) : 'PM-0001';
        $storeData['id_pembelian'] = $newId;

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
            $Penitipan_Barang = Penitipan_Barang::find($item['id_barang']);
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
            'id_alamat' => 'required',
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
            'batas_waktu' => 'required',
            'tanggal_pembelian' => 'required',
            'tanggal_lunas' => '',
            'tanggal_pengiriman-pengambilan' => '',
            'bukti_pembayaran' => '',
        ]);
        $storeData['tanggal_lunas'] = '2000-01-01';
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
            'id_alamat' => 'required',
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
            'bukti_pembayaran' => '',
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
            'id_alamat' => 'required',
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
}
