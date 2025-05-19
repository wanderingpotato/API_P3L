<?php

namespace App\Http\Controllers;

use App\Models\Detail_Donasi;
use App\Models\Donasi;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Penitipan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DonasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getBarangDonasi($id_organisasi)
    {
        // Menjalankan query untuk mendapatkan barang yang didonasikan ke organisasi tertentu
        $barang = DB::table('penitipan__barangs as b')
            ->join('detail__donasis as dd', 'b.Id_barang', '=', 'dd.Id_barang')
            ->join('donasis as d', 'dd.Id_donasi', '=', 'd.Id_donasi')
            ->join('organisasis as o', 'd.Id_organisasi', '=', 'o.Id_organisasi')
            ->select('b.Nama_Barang', 'b.Harga_barang', 'b.Id_kategori', 'b.Deskripsi','b.Status', 'o.name')
            ->where('o.Id_organisasi', 1)
            ->get();

        if ($barang->isEmpty()) {
            return response()->json(['message' => 'Tidak ada barang yang didonasikan.'], 404);
        }
        return response()->json($barang);
    }


    public function index(Request $request)
    {
        $query = Donasi::with('Detail_Donasi');
        if ($request->has('search') && $request->search != '') {
            $query->where('id_Donasi', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Donasi = $query->paginate($perPage);


        return response([
            'message' => 'All Donasi Retrieved',
            'data' => $Donasi
        ], 200);
    }
    public function getData()
    {
        $data = Donasi::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    public function getDataByOrganisasiId($idUser)
    {
        $user = Organisasi::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Donasi::with('Detail_Donasi')->where('Id_Organisasi', $idUser)->get();
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
    public function countDonasi()
    {
        $count = Donasi::count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }
    public function countDonasiByOrganisasi()
    {
        $idUser = Auth::id();
        $user = Organisasi::find($idUser);
        if (is_null($user)) {
            return response(['message' => 'User Not Found'], 404);
        }
        $count = Donasi::where('Id_Organisasi', $idUser)->count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Nama_Penerima' => 'nullable',
            'Tanggal_diberikan' => 'nullable',
            'Tanggal_request' => 'required',
            'Deskripsi' => 'required',
        ]);
        $storeData['Konfirmasi'] = 0;
        $storeData['Tanggal_diberikan'] = '2000-01-01'; //tanggal Null kitas

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Donasi::latest('Id_donasi')->first();
        $newId = $lastId ? 'D-' . str_pad((int) substr($lastId->Id_donasi, 1) + 1, 3, '0', STR_PAD_LEFT) : 'D-001';
        $storeData['Id_donasi'] = $newId;

        $idUser = Auth::id();
        $user = Organisasi::find($idUser);
        if (is_null($user)) {

            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $storeData['Id_organisasi'] = $user->Id_organisasi;


        $Donasi = Donasi::create($storeData);
        return response([
            'message' => 'Donasi Added Successfully',
            'data' => $Donasi,
        ], 200);
    }

    public function storeDashboard(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Nama_Penerima' => 'nullable',
            'Tanggal_diberikan' => 'nullable',
            'Tanggal_request' => 'required',
            'Deskripsi' => 'required',
        ]);
        $storeData['Konfirmasi'] = 0;
        $storeData['Tanggal_diberikan'] = '2000-01-01'; //tanggal Null kitas
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Donasi::latest('Id_donasi')->first();
        $newId = $lastId ? 'D-' . str_pad((int) substr($lastId->Id_donasi, 1) + 1, 3, '0', STR_PAD_LEFT) : 'D-001';
        $storeData['Id_donasi'] = $newId;

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


        $Donasi = Donasi::create($storeData);
        return response([
            'message' => 'Donasi Added Successfully',
            'data' => $Donasi,
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Donasi = Donasi::find($id);

        if ($Donasi) {
            return response([
                'message' => 'Donasi Found',
                'data' => $Donasi
            ], 200);
        }

        return response([
            'message' => 'Donasi Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Donasi = Donasi::find($id);
        if (is_null($Donasi)) {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Deskripsi' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser = Auth::id();
        $user = Organisasi::find($idUser);
        if (is_null($user)) {

            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $storeData['Id_Organisasi'] = $user->Id_Organisasi;

        $Donasi->update($updateData);
        return response([
            'message' => 'Donasi Updated Successfully',
            'data' => $Donasi,
        ], 200);
    }
    public function PenerimaDonasi(Request $request, string $id)
    {
        $Donasi = Donasi::find($id);
        if (is_null($Donasi)) {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'Nama_Penerima' => 'nullable',
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
        if ($user->Id_jabatan == 'J-004') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $Donasi->update($updateData);
        return response([
            'message' => 'Donasi Updated Successfully',
            'data' => $Donasi,
        ], 200);
    }
    public function UpdateKorfirmasi(Request $request, string $id)
    {
        $Donasi = Donasi::find($id);
        if (is_null($Donasi)) {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Nama_Penerima' => 'nullable',
            'Tanggal_diberikan' => 'nullable',
        ]);
        $storeData['Konfirmasi'] = 1;
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
        if ($user->Id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $Donasi->update($updateData);
        foreach ($request->Data as $items) {
            $storeChildData = $items;
            $storeChildData['Id_Donasi'] = $id;
            $Detail_Pembelian = Penitipan_Barang::find($items['Id_Barang']);
            if (is_null($Detail_Pembelian)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $validate = Validator::make($storeChildData, [
                'Id_Donasi' => 'required',
                'Id_Barang' => 'required',
            ]);
            $storeChildData['Id_Penitip'] = $Detail_Pembelian->Id_Penitip;
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            Detail_Donasi::create($storeChildData);
        }
        return response([
            'message' => 'Donasi Updated Successfully',
            'data' => $Donasi,
        ], 200);
    }
    public function UpdateDetailDonasi(Request $request, string $id)
    {
        $Donasi = Donasi::find($id);
        if (is_null($Donasi)) {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Nama_Penerima' => 'nullable',
            'Tanggal_diberikan' => 'nullable',
        ]);
        $storeData['Konfirmasi'] = 1;
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
        if ($user->Id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $Donasi->update($updateData);
        if ($request->has('Id_Barang') && $request->Id_Barang != '') {
            Detail_Donasi::where('Id_Donasi', $id)->delete();
            $storeChildData['Id_Barang'] = $updateData['Id_Barang'];
            $storeChildData['Id_Donasi'] = $id;
            $Detail_Pembelian = Penitipan_Barang::find($storeChildData['Id_Barangs']);
            $storeChildData['Id_Penitip'] = $Detail_Pembelian->Id_Penitip;
            if (is_null($Detail_Pembelian)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $validate = Validator::make($storeChildData, [
                'Id_Donasi' => 'required',
                'Id_Barang' => 'required',
                'Id_Penitip' => 'required',
            ]);
            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            Detail_Donasi::create($storeChildData);
        }
        return response([
            'message' => 'Donasi Updated Successfully',
            'data' => $Donasi,
        ], 200);
    }
    public function updateDashboard(Request $request, string $id)
    {
        $Donasi = Donasi::find($id);
        if (is_null($Donasi)) {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Nama_Penerima' => 'nullable',
            'Tanggal_diberikan' => 'nullable',
            'Tanggal_request' => 'required',
            'Deskripsi' => 'required',
            'Konfirmasi' => 'required',
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
        if ($user->Id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $Donasi->update($updateData);

        return response([
            'message' => 'Donasi Updated Successfully',
            'data' => $Donasi,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Donasi = Donasi::find($id);

        if (is_null($Donasi)) {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }
        if ($Donasi->delete()) {
            return response([
                'message' => 'Donasi Deleted Successfully',
                'data' => $Donasi,
            ], 200);
        }

        return response([
            'message' => 'Delete Donasi Failed',
            'data' => null,
        ], 400);
    }
}
