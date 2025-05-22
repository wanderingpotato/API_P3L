<?php

namespace App\Http\Controllers;

use App\Models\Detail_Donasi;
use App\Models\Donasi;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailDonasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DetailDonasi = Detail_Donasi::inRandomOrder()->get();

        return response([
            'message' => 'AllDetail_Donasi Retrieved',
            'data' => $DetailDonasi
        ], 200);
    }
    public function getData()
    {
        $data = Detail_Donasi::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function showDetailDonasibyDonasi($id)
    {
        $user = Donasi::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $DetailDonasi = Detail_Donasi::where('id_donasi', $user->id_donasi)->get();
        return response([
            'message' => 'DetailDonasi of ' . $user->name . ' Retrieved',
            'data' => $DetailDonasi
        ], 200);
    }


    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_donasi' => 'required',
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


        $DetailDonasi = Detail_Donasi::create($storeData);
        return response([
            'message' => 'DetailDonasi Added Successfully',
            'data' => $DetailDonasi,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $DetailDonasi = Detail_Donasi::find($id);

        if ($DetailDonasi) {
            return response([
                'message' => 'DetailDonasi Found',
                'data' => $DetailDonasi
            ], 200);
        }

        return response([
            'message' => 'DetailDonasi Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $DetailDonasi = Detail_Donasi::find($id);
        if (is_null($DetailDonasi)) {
            return response([
                'message' => 'DetailDonasi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_donasi' => 'required',
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

        $DetailDonasi->update($updateData);

        return response([
            'message' => 'DetailDonasi Updated Successfully',
            'data' => $DetailDonasi,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $DetailDonasi = Detail_Donasi::find($id);

        if (is_null($DetailDonasi)) {
            return response([
                'message' => 'DetailDonasi Not Found',
                'data' => null
            ], 404);
        }

        if ($DetailDonasi->delete()) {
            return response([
                'message' => 'DetailDonasi Deleted Successfully',
                'data' => $DetailDonasi,
            ], 200);
        }

        return response([
            'message' => 'DeleteDetail_Donasi Failed',
            'data' => null,
        ], 400);
    }
}
