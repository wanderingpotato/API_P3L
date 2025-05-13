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
        $query = Pembelian::with('Detail_Pembelian');
        if ($request->has('search') && $request->search != '') {
            $query->where('id_Pembelian', 'like', '%' . $request->search . '%');
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
        $Pembelian = Pembelian::with('detail__pembelians')->where('Id_Pembeli', $user->Id_Pembeli)->get();
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
        $Pembelian = Pembelian::with(['detail__pembelians', 'alamat'])->where('Id_Pembelian', $id)->get();
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
        $count = Pembelian::where('Id_Pembeli', $idUser)->count();
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
            'Bukti_Pembayaran' => 'required',
        ]);
        if ($request->hasFile('Bukti_Pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('Bukti_Pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->Bukti_Pembayaran);
            $updateData['Bukti_Pembayaran'] = $uploadedImageResponse;
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
        if ($user->Id_jabatan == 'J-003') {
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
            'Status' => 'required',
            'Status_Pengiriman' => 'required',
            'Tanggal_Lunas' => 'required',
            'Tanggal_Pengiriman-Pengambilan' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $Pembelian->update($updateData);
        foreach ($request->Data as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['Id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $UpdateChild['Status'] = 'DiBeli';
            $UpdateChild['Tanggal_Laku'] = $updateData['Tanggal_Lunas'];
            $validate = Validator::make($UpdateChild, [
                'Tanggal_Laku' => 'required',
                'Status' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            $Penitipan_Barang->update($UpdateChild);
            if ($Pembelian->status == "Selesai") {
                $storeChildData['Id_barang'] = $Penitipan_Barang->Id_barang;
                $storeChildData['Bonus_Penitip'] = 0;
                $storeChildData['Komisi_Hunter'] = 0;
                $storeChildData['Komisi_Toko'] = $Penitipan_Barang->Harga_Barang * (20 / 100);
                $KomisiTemp = $Penitipan_Barang->Harga_Barang * (20 / 100);
                if ($Penitipan_Barang->Hunter == 1) {
                    $storeChildData['Id_pegawai'] = $Penitipan_Barang->Id_pegawai;
                    $storeChildData['Komisi_Hunter'] = $KomisiTemp * (5 / 100);
                    $storeChildData['Komisi_Toko'] = $storeChildData['Komisi_Toko'] - $storeChildData['Komisi_Hunter'];
                }
                if (Carbon::parse($Penitipan_Barang->Tanggal_penitipan)->diffInDays(Carbon::parse($Penitipan_Barang->Tanggal_Laku)) <= 7) {
                    $storeChildData['Id_penitip'] = $Penitipan_Barang->Id_penitip;
                    $storeChildData['Bonus_Penitip'] = $KomisiTemp * (10 / 100);
                    $storeChildData['Komisi_Toko'] = $storeChildData['Komisi_Toko'] - $storeChildData['Bonus_Penitip'];
                }
                $storeChildData['Komisi_Penitip'] = $Penitipan_Barang->Harga_Barang - ($storeChildData['Komisi_Toko'] + $storeChildData['Komisi_Hunter']);

                $lastId = Komisi::latest('Id_komisi')->first();
                $newId = $lastId ? 'K-' . str_pad((int) substr($lastId->Id_komisi, 2) + 1, 3, '0', STR_PAD_LEFT) : 'K-001';
                $storeChildData['Id_komisi'] = $newId;

                $storeChildData['Tanggal_Komisi'] = $request->Tanggal_Komisi;
                $validate = Validator::make($storeChildData, [
                    'Id_barang' => 'required',
                    'Komisi_Penitip' => 'required',
                    'Komisi_Toko' => 'required',
                    'Tanggal_Komisi' => 'required',
                ]);
                Komisi::create($storeChildData);

                if ($storeChildData['Id_penitip'] != null && $Penitipan_Barang->Id_penitip != null) {
                    $Penitip = Penitip::where('Id_penitip', $Penitipan_Barang->Id_penitip)->get();
                    $UpdateDataPenitip['saldo'] = $Penitip->saldo + $storeChildData['Komisi_Penitip'];
                    $total =  $storeChildData['Komisi_Penitip'];
                    if ($storeChildData['Bonus_Penitip'] != 0) {
                        $UpdateDataPenitip['saldo'] = $UpdateDataPenitip['saldo'] +  $storeChildData['Bonus_Penitip'];
                        $total = $total +  $storeChildData['Bonus_Penitip'];
                    }
                    $Penitip->update($UpdateDataPenitip);
                    $currentDate = Carbon::now();
                    $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->get();
                    if (is_null($DataPenjualan)) {
                        $StoreTambah['Id_penitip'] = $request->Id_penitip;
                        $StoreTambah['total'] = $total;
                        $StoreTambah['month'] = $currentDate->toDateString();
                        $StoreTambah['Bonus_Pendapatan'] = 0;
                        Detail_Pendapatan::create($StoreTambah);
                    } else {
                        $StoreTambah['Id_penitip'] = $request->Id_penitip;
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
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_alamat' => 'required',
            'Id_Pegawai' => '',
            'Dilivery' => 'required',
            'Status' => 'required',
            'Status_Pengiriman' => 'required',
            'PointYgDidapat' => '',
            'PointCurrent' => '',
            'PointDigunakan' => 'required',
            'Potongan_Harga' => '',
            'Harga_Barang' => '',
            'Ongkir' => 'required',
            'Batas_Waktu' => 'required',
            'Tanggal_Pembelian' => 'required',
            'Tanggal_Lunas' => '',
            'Tanggal_Pengiriman-Pengambilan' => '',
            'Bukti_Pembayaran' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $storeData['Tanggal_Lunas'] = '2000-01-01';
        $storeData['Tanggal_Pengiriman-Pengambilan'] = '2000-01-01';
        $storeData['Potongan_Harga'] = intdiv($storeData['PointDigunakan'], 100) * 10000;

        $lastId = Pembelian::latest('Id_pembelian')->first();
        $newId = $lastId ? 'PM-' . str_pad((int) substr($lastId->Id_pembelian, 1) + 1, 3, '0', STR_PAD_LEFT) : 'PM-001';
        $storeData['Id_pembelian'] = $newId;

        //Nyari Sesuai User
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $storeData['PointCurrent'] = $user->poin;
        $storeData['Id_Pembeli'] = $user->Id_Pembeli;

        //Ngitung Total Harga
        $storeData['Harga_Barang'] = 0;
        $p = 0;
        foreach ($request->Data as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['Id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $p += (int)$Penitipan_Barang->Harga_Barang;
        }
        $storeData['Harga_Barang'] = $p;


        if ($request->hasFile('Bukti_Pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('Bukti_Pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            $storeData['Bukti_Pembayaran'] = $uploadedImageResponse;
        }

        $Pembelian = Pembelian::create($storeData);
        foreach ($request->Data as $items) {
            $storeChildData = $items;
            $storeChildData['id_Pembelian'] = $newId;
            $Penitipan_Barang = Penitipan_Barang::find($item['Id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $storeChildData['Id_barang'] = $Penitipan_Barang->Id_barang;
            $storeChildData['Id_penitip'] = $Penitipan_Barang->Id_penitip;
            $validate = Validator::make($storeChildData, [
                'id_Pembelian' => 'required',
                'Id_barang' => 'required',
                'Id_penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }

            Detail_Pembelian::create($storeChildData);
            $UpdateChild['Status'] = 'DiBeli';
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
            'Id_alamat' => 'required',
            'Id_Pegawai' => '',
            'Dilivery' => 'required',
            'Status' => 'required',
            'Status_Pengiriman' => 'required',
            'PointYgDidapat' => '',
            'PointCurrent' => 'required',
            'PointDigunakan' => 'required',
            'Potongan_Harga' => '',
            'Harga_Barang' => '',
            'Ongkir' => 'required',
            'Batas_Waktu' => 'required',
            'Tanggal_Pembelian' => 'required',
            'Tanggal_Lunas' => '',
            'Tanggal_Pengiriman-Pengambilan' => '',
            'Bukti_Pembayaran' => '',
        ]);
        $storeData['Tanggal_Lunas'] = '2000-01-01';
        $storeData['Tanggal_Pengiriman-Pengambilan'] = '2000-01-01';
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Pembelian::latest('Id_pembelian')->first();
        $newId = $lastId ? 'PM-' . str_pad((int) substr($lastId->Id_pembelian, 1) + 1, 3, '0', STR_PAD_LEFT) : 'PM-001';
        $storeData['Id_pembelian'] = $newId;

        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        if ($user->Id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $storeData['Potongan_Harga'] = intdiv($storeData['PointDigunakan'], 100) * 10000;


        $storeChildData['Id_barang'] = $storeData['Id_barang'];
        $storeChildData['id_Pembelian'] = $newId;
        $Penitipan_Barang = Penitipan_Barang::find($storeChildData['Id_barang']);
        if (is_null($Penitipan_Barang)) {
            return response([
                'message' => 'Barber Not found',
            ], 404);
        }
        $storeChildData['Id_penitip'] = $Penitipan_Barang->Id_penitip;
        $storeData['Harga_Barang'] = $Penitipan_Barang->Harga_Barang;
        $Pembelian = Pembelian::create($storeData);
        $validate = Validator::make($storeChildData, [
            'id_Pembelian' => 'required',
            'Id_barang' => 'required',
            'Id_penitip' => 'required',
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
            'Id_alamat' => 'required',
            'Id_Pegawai' => '',
            'Dilivery' => 'required',
            'Status' => 'required',
            'Status_Pengiriman' => 'required',
            'PointYgDidapat' => '',
            'PointCurrent' => '',
            'PointDigunakan' => 'required',
            'Potongan_Harga' => '',
            'Harga_Barang' => '',
            'Ongkir' => 'required',
            'Batas_Waktu' => 'required',
            'Tanggal_Pembelian' => 'required',
            'Tanggal_Lunas' => '',
            'Tanggal_Pengiriman-Pengambilan' => '',
            'Bukti_Pembayaran' => '',
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
        $updateData['Harga_Barang'] = 0;
        $p = 0;
        Detail_Pembelian::where('id_Pembelian', $id)->delete();
        foreach ($request->Data as $item) {
            $Penitipan_Barang = Penitipan_Barang::find($item['Id_barang']);
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Penitipan_Barang Not found',
                ], 404);
            }
            $p += (int)$Penitipan_Barang->Harga_Barang;
            $storeChildData = $item;
            $storeChildData['id_Pembelian'] = $id;
            $storeChildData['Id_penitip'] = $Penitipan_Barang->Id_penitip;
            $validate = Validator::make($storeChildData, [
                'id_Pembelian' => 'required',
                'Id_barang' => 'required',
                'Id_penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            Detail_Pembelian::create($storeChildData);
        }
        $updateData['Potongan_Harga'] = intdiv($updateData['PointDigunakan'], 100) * 10000;
        if ($request->hasFile('Bukti_Pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('Bukti_Pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->Bukti_Pembayaran);
            $updateData['Bukti_Pembayaran'] = $uploadedImageResponse;
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
            'Id_alamat' => 'required',
            'Id_Pegawai' => '',
            'Dilivery' => 'required',
            'Status' => 'required',
            'Status_Pengiriman' => 'required',
            'PointYgDidapat' => '',
            'PointCurrent' => '',
            'PointDigunakan' => 'required',
            'Potongan_Harga' => '',
            'Harga_Barang' => '',
            'Ongkir' => 'required',
            'Batas_Waktu' => 'required',
            'Tanggal_Pembelian' => 'required',
            'Tanggal_Lunas' => '',
            'Tanggal_Pengiriman-Pengambilan' => '',
            'Bukti_Pembayaran' => '',
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
            Detail_Pembelian::where('id_Pembelian', $id)->delete();
            $storeChildData['Id_barang'] = $updateData['Id_barang'];
            $storeChildData['id_Pembelian'] = $id;
            $Penitipan_Barang = Penitipan_Barang::find($storeChildData['Id_barang']);
            $updateData['Harga_Barang'] = $Penitipan_Barang->Harga_Barang;
            $updateData['Potongan_Harga'] = intdiv($updateData['PointDigunakan'], 100) * 10000;
            $storeChildData['Id_penitip'] = $Penitipan_Barang->Id_penitip;
            if (is_null($Penitipan_Barang)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $validate = Validator::make($storeChildData, [
                'id_Pembelian' => 'required',
                'Id_barang' => 'required',
                'Id_penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            Detail_Pembelian::create($storeChildData);
        }
        if ($request->hasFile('Bukti_Pembayaran')) {
            $uploadFolder = 'BuktiPembayaran';
            $image = $request->file('Bukti_Pembayaran');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('BuktiPembayaran/' . $Pembelian->Bukti_Pembayaran);
            $updateData['Bukti_Pembayaran'] = $uploadedImageResponse;
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
