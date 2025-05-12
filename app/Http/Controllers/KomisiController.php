<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pendapatan;
use App\Models\Komisi;
use App\Models\Pegawai;
use App\Models\Penitip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KomisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Komisi = Komisi::inRandomOrder()->get();

        return response([
            'message' => 'AllKomisi Retrieved',
            'data' => $Komisi
        ], 200);
    }
    public function getData()
    {
        $data = Komisi::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function showKomisibyPegawai($id)
    {
        $user = Pegawai::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Komisi = Komisi::where('Id_pegawai', $user->id)->get();
        return response([
            'message' => 'Komisi of ' . $user->name . ' Retrieved',
            'data' => $Komisi
        ], 200);
    }
    public function showKomisibyPenitip($id)
    {
        $user = Penitip::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Komisi = Komisi::where('Id_penitip', $user->id)->get();
        return response([
            'message' => 'Komisi of ' . $user->name . ' Retrieved',
            'data' => $Komisi
        ], 200);
    }



    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_barang' => 'required',
            'Id_pegawai' => 'nullable',
            'Id_penitip' => 'nullable',
            'Bonus_Penitip' => 'nullable',
            'Komisi_Penitip' => 'required',
            'Komisi_Toko' => 'required',
            'Komisi_Hunter' => 'nullable',
            'Tanggal_Komisi' => 'required',
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

        $lastId = Komisi::latest('Id_komisi')->first();
        $newId = $lastId ? 'K-' . str_pad((int) substr($lastId->Id_komisi, 2) + 1, 3, '0', STR_PAD_LEFT) : 'K-001';
        $storeData['Id_komisi'] = $newId;
        $Komisi = Komisi::create($storeData);

        if ($request->has('Id_penitip')  && $request->Id_penitip != null) {
            $Penitip = Penitip::where('Id_penitip', $request->Id_penitip)->get();
            $UpdateDataPenitip['saldo'] = $Penitip->saldo + $storeData['Komisi_Penitip'];
            $total =  $storeData['Komisi_Penitip'];
            if ($request->has('Bonus_Penitip')  && $request->Bonus_Penitip != null) {
                $UpdateDataPenitip['saldo'] = $UpdateDataPenitip['saldo'] +  $storeData['Bonus_Penitip'];
                $total = $total +  $storeData['Bonus_Penitip'];
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

        return response([
            'message' => 'Komisi Added Successfully',
            'data' => $Komisi,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Komisi = Komisi::find($id);

        if ($Komisi) {
            return response([
                'message' => 'Komisi Found',
                'data' => $Komisi
            ], 200);
        }

        return response([
            'message' => 'Komisi Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Komisi = Komisi::find($id);
        if (is_null($Komisi)) {
            return response([
                'message' => 'Komisi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_barang' => 'required',
            'Id_pegawai' => 'nullable',
            'Id_penitip' => 'nullable',
            'Bonus_Penitip' => 'nullable',
            'Komisi_Penitip' => 'required',
            'Komisi_Toko' => 'required',
            'Komisi_Hunter' => 'nullable',
            'Tanggal_Komisi' => 'required',
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

        $Komisi->update($updateData);

        if ($request->has('Id_penitip')  && $request->Id_penitip != null) {
            $Penitip = Penitip::where('Id_penitip', $request->Id_penitip)->get();
            $Penitip->saldo = ($Penitip->saldo - $Komisi->Komisi_Penitip) + $updateData['Komisi_Penitip'];
            $total =  $updateData['Komisi_Penitip'];
            if ($request->has('Bonus_Penitip')  && $request->Bonus_Penitip != null) {
                $Penitip->saldo = ($Penitip->saldo - $Komisi->Bonus_Penitip) +  $updateData['Bonus_Penitip'];
                $total = $total +  $updateData['Bonus_Penitip'];
            }
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
                $StoreTambah['total'] = ($DataPenjualan->total - ($Komisi->Komisi_Penitip + $Komisi->Bonus_Penitip)) + $total;
                $DataPenjualan->update($StoreTambah);
            }
        }

        return response([
            'message' => 'Komisi Updated Successfully',
            'data' => $Komisi,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Komisi = Komisi::find($id);

        if (is_null($Komisi)) {
            return response([
                'message' => 'Komisi Not Found',
                'data' => null
            ], 404);
        }

        if ($Komisi->delete()) {
            return response([
                'message' => 'Komisi Deleted Successfully',
                'data' => $Komisi,
            ], 200);
        }

        return response([
            'message' => 'DeleteKomisi Failed',
            'data' => null,
        ], 400);
    }
}
