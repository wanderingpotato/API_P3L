<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pembelian;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DetailPembelian = Detail_Pembelian::inRandomOrder()->get();

        return response([
            'message' => 'AllDetail_Pembelian Retrieved',
            'data' => $DetailPembelian
        ], 200);
    }
    public function getData()
    {
        $data = Detail_Pembelian::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function showDetailPembelianbyPembelian($id)
    {
        $user = Pembelian::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $DetailPembelian = Detail_Pembelian::where('id_pembelian', $user->id_pembelian)->get();
        return response([
            'message' => 'DetailPembelian of ' . $user->name . ' Retrieved',
            'data' => $DetailPembelian
        ], 200);
    }

    public function countCart()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $Pembelian = Pembelian::where('status', 'Keranjang')->where('id_pembeli', $user->id_pembeli)->first();

        $DetailPembelian = Detail_Pembelian::where('id_pembelian', $Pembelian->id_pembelian)->count();

        if ($DetailPembelian) {
            return response([
                'message' => 'DetailPembelian Found',
                'data' => $DetailPembelian
            ], 200);
        }

        return response([
            'message' => 'DetailPembelian Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_pembelian' => 'required',
            'id_barang' => 'required',
            'id_penitip' => 'required',
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


        $DetailPembelian = Detail_Pembelian::create($storeData);
        return response([
            'message' => 'DetailPembelian Added Successfully',
            'data' => $DetailPembelian,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $DetailPembelian = Detail_Pembelian::where('id_pembelian', $id)->get();

        if ($DetailPembelian) {
            return response([
                'message' => 'DetailPembelian Found',
                'data' => $DetailPembelian
            ], 200);
        }

        return response([
            'message' => 'DetailPembelian Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $DetailPembelian = Detail_Pembelian::find($id);
        if (is_null($DetailPembelian)) {
            return response([
                'message' => 'DetailPembelian Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_pembelian' => 'required',
            'id_barang' => 'required',
            'id_penitip' => 'required',
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

        $DetailPembelian->update($updateData);

        return response([
            'message' => 'DetailPembelian Updated Successfully',
            'data' => $DetailPembelian,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $DetailPembelian = Detail_Pembelian::find($id);

        if (is_null($DetailPembelian)) {
            return response([
                'message' => 'DetailPembelian Not Found',
                'data' => null
            ], 404);
        }

        if ($DetailPembelian->delete()) {
            return response([
                'message' => 'DetailPembelian Deleted Successfully',
                'data' => $DetailPembelian,
            ], 200);
        }

        return response([
            'message' => 'DeleteDetail_Pembelian Failed',
            'data' => null,
        ], 400);
    }
}
