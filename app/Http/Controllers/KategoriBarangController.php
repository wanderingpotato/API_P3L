<?php

namespace App\Http\Controllers;

use App\Models\Kategori_Barang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kategori_Barang::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Kategori_Barang = $query->paginate($perPage);

        return response([
            'message' => 'All Kategori Barang Retrieved',
            'data' => $Kategori_Barang
        ], 200);
    }
    public function getData()
    {
        $data = Kategori_Barang::get();

        return response([
            'message' => 'All Kategori Barang Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function showKategoriBarangWithPenitipanBarangByKategori_BarangId(string $id)
    {
        $Kategori_Barang = Kategori_Barang::with('Penitipan_Barang')->find($id);

        if ($Kategori_Barang) {
            return response([
                'message' => 'Kategori Barang Found',
                'data' => $Kategori_Barang
            ], 200);
        }

        return response([
            'message' => 'Kategori Barang Not Found',
            'data' => null
        ], 404);
    }
    public function showKategoriBarangWithPenitipanBarang()
    {
        $data = Kategori_Barang::with('Penitipan_Barang')->get();

        return response([
            'message' => 'All Kategori Barang Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_kategori' => 'required',
            'sub_kategori' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser =  Auth::id();
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

        $lastId = Kategori_Barang::latest('id_kategori')->first();
        $newId = $lastId ? 'K-' . str_pad((int) substr($lastId->id_kategori, 2) + 1, 4, '0', STR_PAD_LEFT) : 'K-0001';
        $storeData['id_kategori'] = $newId;

        $Kategori_Barang = Kategori_Barang::create($storeData);
        return response([
            'message' => 'Kategori Barang Added Successfully',
            'data' => $Kategori_Barang,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Kategori_Barang = Kategori_Barang::find($id);

        if ($Kategori_Barang) {
            return response([
                'message' => 'Kategori Barang Found',
                'data' => $Kategori_Barang
            ], 200);
        }

        return response([
            'message' => 'Kategori Barang Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Kategori_Barang = Kategori_Barang::find($id);
        if (is_null($Kategori_Barang)) {
            return response([
                'message' => 'Kategori Barang Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_kategori' => 'required',
            'sub_kategori' => 'required',
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
        if ($user->id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $Kategori_Barang->update($updateData);

        return response([
            'message' => 'Kategori Barang Updated Successfully',
            'data' => $Kategori_Barang,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Kategori_Barang = Kategori_Barang::find($id);

        if (is_null($Kategori_Barang)) {
            return response([
                'message' => 'Kategori Barang Not Found',
                'data' => null
            ], 404);
        }

        if ($Kategori_Barang->delete()) {
            return response([
                'message' => 'Kategori Barang Deleted Successfully',
                'data' => $Kategori_Barang,
            ], 200);
        }

        return response([
            'message' => 'Delete Kategori Barang Failed',
            'data' => null,
        ], 400);
    }
}
