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
            ->select('b.Nama_Barang', 'b.Harga_barang', 'b.Id_kategori', 'b.Deskripsi', 'b.Status', 'o.name')
            ->where('o.Id_organisasi', 1)
            ->get();

        if ($barang->isEmpty()) {
            return response()->json(['message' => 'Tidak ada barang yang didonasikan.'], 404);
        }
        return response()->json($barang);
    }


    public function index(Request $request)
    {
        $query = Donasi::with('Detail__Donasi');
        if ($request->has('search') && $request->search != '') {
            $query->where('id_donasi', 'like', '%' . $request->search . '%');
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
    public function getDataWithPenitipanBarang()
    {
        $data = Donasi::with(['Detail_Donasi', 'DetailDonasis.Barang'])->get();
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
    public function getDataWithPenitipanBarangById($id)
    {
        $data = Donasi::with(['Detail_Donasi', 'DetailDonasis.Barang'])->find($id);
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
    public function getDataWithPenitipanBarangByIdOrganisasi($id)
    {
        $data = Donasi::with('penitipan__barangs')->where('id_organisasi', $id)->get();
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
    public function getDataByOrganisasiId($idUser)
    {
        $user = Organisasi::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Donasi::with('detail__donasis')->where('id_organisasi', $idUser)->get();
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
        $count = Donasi::where('id_organisasi', $idUser)->count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_penerima' => 'nullable',
            'tanggal_diberikan' => 'nullable',
            'tanggal_request' => 'required',
            'deskripsi' => 'required',
        ]);
        $storeData['konfirmasi'] = 0;
        $storeData['tanggal_diberikan'] = '2000-01-01'; //tanggal Null kitas

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Donasi::latest('id_donasi')->first();
        $newId = $lastId ? 'D-' . str_pad((int) substr($lastId->id_donasi, 2) + 1, 4, '0', STR_PAD_LEFT) : 'D-0001';
        $storeData['id_donasi'] = $newId;

        $idUser = Auth::id();
        $user = Organisasi::find($idUser);
        if (is_null($user)) {

            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $storeData['id_organisasi'] = $user->id_organisasi;


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
            'nama_penerima' => 'nullable',
            'tanggal_diberikan' => 'nullable',
            'tanggal_request' => 'required',
            'deskripsi' => 'required',
        ]);
        $storeData['konfirmasi'] = 0;
        $storeData['tanggal_diberikan'] = '2000-01-01'; //tanggal Null kitas
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Donasi::latest('id_donasi')->first();
        $newId = $lastId ? 'D-' . str_pad((int) substr($lastId->id_donasi, 2) + 1, 4, '0', STR_PAD_LEFT) : 'D-0001';
        $storeData['id_donasi'] = $newId;

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
            'deskripsi' => 'required',
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
        $storeData['id_organisasi'] = $user->id_organisasi;

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
            'nama_penerima' => 'nullable',
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
        if ($user->id_jabatan == 'J-004') {
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
            'nama_penerima' => 'nullable',
            'tanggal_diberikan' => 'nullable',
        ]);
        $storeData['konfirmasi'] = 1;
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
        if ($user->id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $Donasi->update($updateData);
        foreach ($request->Data as $items) {
            $storeChildData = $items;
            $storeChildData['id_donasi'] = $id;
            $Detail_Pembelian = Penitipan_Barang::find($items['id_barang']);
            if (is_null($Detail_Pembelian)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $validate = Validator::make($storeChildData, [
                'id_donasi' => 'required',
                'id_barang' => 'required',
            ]);
            $storeChildData['id_penitip'] = $Detail_Pembelian->id_penitip;
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
            'nama_penerima' => 'nullable',
            'tanggal_diberikan' => 'nullable',
        ]);
        $storeData['konfirmasi'] = 1;
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
        if ($user->id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $Donasi->update($updateData);
        if ($request->has('id_barang') && $request->id_barang != '') {
            Detail_Donasi::where('id_donasi', $id)->delete();
            $storeChildData['id_barang'] = $updateData['id_barang'];
            $storeChildData['id_donasi'] = $id;
            $Detail_Pembelian = Penitipan_Barang::find($storeChildData['id_barang']);
            $storeChildData['id_penitip'] = $Detail_Pembelian->id_penitip;
            if (is_null($Detail_Pembelian)) {
                return response([
                    'message' => 'Barber Not found',
                ], 404);
            }
            $validate = Validator::make($storeChildData, [
                'id_donasi' => 'required',
                'id_barang' => 'required',
                'id_penitip' => 'required',
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
            'nama_penerima' => 'nullable',
            'tanggal_diberikan' => 'nullable',
            'tanggal_request' => 'required',
            'deskripsi' => 'required',
            'konfirmasi' => 'required',
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
        if ($user->id_jabatan == 'J-003') {
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
