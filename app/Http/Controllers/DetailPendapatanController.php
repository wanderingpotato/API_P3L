<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pendapatan;
use App\Models\Pegawai;
use App\Models\Penitip;
use Carbon\Carbon;
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
    public function showDetailPendapatanbyPenitip($id)
    {
        $user = Penitip::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $DetailPendapatan = Detail_Pendapatan::where('id_penitip', $user->id_penitip)->get();
        return response([
            'message' => 'DetailPendapatan of ' . $user->name . ' Retrieved',
            'data' => $DetailPendapatan
        ], 200);
    }


    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_penitip' => 'required',
            'total' => 'required',
            'month' => 'required',
            'bonus_pendapatan' => '',
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

        $lastId = Detail_Pendapatan::latest('id_detail_pendapatan')->first();
        $newId = $lastId ? 'DP-' . str_pad((int) substr($lastId->id_detail_pendapatan, 3) + 1, 4, '0', STR_PAD_LEFT) : 'DP-0001';
        $storeData['id_detail_pendapatan'] = $newId;
        $DetailPendapatan = Detail_Pendapatan::create($storeData);
        return response([
            'message' => 'DetailPendapatan Added Successfully',
            'data' => $DetailPendapatan,
        ], 200);
    }


    public function setTopSeller()
    {
        $firstOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $topDetailPendapatan = Detail_Pendapatan::where('month', $firstOfMonth)->orderBy('total', 'desc')->first();
        if (is_null($topDetailPendapatan)) {
            return response([
                'message' => 'Toal Not Found'
            ], 404);
        }
        $topDetailPendapatan->bonus_pendapatan = $topDetailPendapatan->total * 0.01;
        $topDetailPendapatan->save();
        $user = Penitip::find($topDetailPendapatan->id_penitip);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $user->badge = 1;
        $user->saldo = $user->saldo + $topDetailPendapatan->bonus_pendapatan;
        $user->update();
        return response([
            'message' => 'DetailPendapatan Added Successfully',
            'data' => $topDetailPendapatan,
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
            'id_penitip' => 'required',
            'total' => 'required',
            'month' => 'required',
            'bonus_pendapatan' => 'required',
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
