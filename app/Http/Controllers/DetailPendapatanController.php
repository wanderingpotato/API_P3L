<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pendapatan;
use App\Models\Pegawai;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailPendapatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DetailPendapatan = Detail_Pendapatan::inRandomOrder()->get();

        return response([
            'message' => 'AllDetail_Pendapatan Retrieved',
            'data' => $DetailPendapatan
        ], 200);
    }
    public function getData()
    {
        $data = Detail_Pendapatan::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function showDetailPendapatanbyUser($id)
    {
        $user = Penitip::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $DetailPendapatan = Detail_Pendapatan::where('Id_penitip', $user->id)->get();
        return response([
            'message' => 'DetailPendapatan of ' . $user->name . ' Retrieved',
            'data' => $DetailPendapatan
        ], 200);
    }


    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_penitip' => 'required',
            'total' => 'required',
            'month' => 'required',
            'Bonus_Pendapatan' => 'required',
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

        $lastId = Detail_Pendapatan::latest('Id_DetailPendapatan')->first();
        $newId = $lastId ? 'DP-' . str_pad((int) substr($lastId->Id_DetailPendapatan, 2) + 1, 3, '0', STR_PAD_LEFT) : 'DP-001';
        $storeData['Id_DetailPendapatan'] = $newId;
        $DetailPendapatan = Detail_Pendapatan::create($storeData);
        return response([
            'message' => 'DetailPendapatan Added Successfully',
            'data' => $DetailPendapatan,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $DetailPendapatan = Detail_Pendapatan::find($id);

        if ($DetailPendapatan) {
            return response([
                'message' => 'DetailPendapatan Found',
                'data' => $DetailPendapatan
            ], 200);
        }

        return response([
            'message' => 'DetailPendapatan Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $DetailPendapatan = Detail_Pendapatan::find($id);
        if (is_null($DetailPendapatan)) {
            return response([
                'message' => 'Detail Pendapatan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_penitip' => 'required',
            'total' => 'required',
            'month' => 'required',
            'Bonus_Pendapatan' => 'required',
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

        $DetailPendapatan->update($updateData);

        return response([
            'message' => 'Detail Pendapatan Updated Successfully',
            'data' => $DetailPendapatan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $DetailPendapatan = Detail_Pendapatan::find($id);

        if (is_null($DetailPendapatan)) {
            return response([
                'message' => 'Detail Pendapatan Not Found',
                'data' => null
            ], 404);
        }

        if ($DetailPendapatan->delete()) {
            return response([
                'message' => 'Detail Pendapatan Deleted Successfully',
                'data' => $DetailPendapatan,
            ], 200);
        }

        return response([
            'message' => 'Delete Detail_Pendapatan Failed',
            'data' => null,
        ], 400);
    }
}
