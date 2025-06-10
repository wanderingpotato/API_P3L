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
    public function index(Request $request)
    {
        $query = Komisi::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('id_komisi', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Komisi = $query->paginate($perPage);


        return response([
            'message' => 'All Komisi Retrieved',
            'data' => $Komisi
        ], 200);
    }
    public function getData()
    {
        $data = Komisi::all();

        return response([
            'message' => 'All Komisi Retrieved',
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
        $Komisi = Komisi::where('id_pegawai', $user->id_pegawai)->get();
        return response([
            'message' => 'Komisi of ' . $user->name . ' Retrieved',
            'data' => $Komisi
        ], 200);
    }

    public function countKomisibyPegawai($id)
    {
        $user = Pegawai::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Komisi = Komisi::where('id_pegawai', $user->id_pegawai)->sum('komisi_hunter');
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
        $Komisi = Komisi::where('id_penitip', $user->id_penitip)->get();
        return response([
            'message' => 'Komisi of ' . $user->name . ' Retrieved',
            'data' => $Komisi
        ], 200);
    }



    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_barang' => 'required',
            'id_pegawai' => 'nullable',
            'id_penitip' => 'nullable',
            'bonus_penitip' => 'nullable',
            'komisi_penitip' => 'required',
            'komisi_toko' => 'required',
            'komisi_hunter' => 'nullable',
            'tanggal_komisi' => 'required',
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

        $lastId = Komisi::latest('id_komisi')->first();
        $newId = $lastId ? 'K-' . str_pad((int) substr($lastId->id_komisi, 2) + 1, 4, '0', STR_PAD_LEFT) : 'K-0001';
        $storeData['id_komisi'] = $newId;
        $Komisi = Komisi::create($storeData);

        if ($request->has('id_penitip')  && $request->id_penitip != null) {
            $Penitip = Penitip::find($request->id_penitip);
            $UpdateDataPenitip['saldo'] = $Penitip->saldo + $storeData['komisi_penitip'];
            $total =  $storeData['komisi_penitip'];
            if ($request->has('bonus_penitip')  && $request->bonus_penitip != null) {
                $UpdateDataPenitip['saldo'] = $UpdateDataPenitip['saldo'] +  $storeData['bonus_penitip'];
                $total = $total +  $storeData['bonus_penitip'];
            }
            $Penitip->update($UpdateDataPenitip);
            $currentDate = Carbon::now();
            $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->first();
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
            'id_barang' => 'required',
            'id_pegawai' => 'nullable',
            'id_penitip' => 'nullable',
            'bonus_penitip' => 'nullable',
            'komisi_penitip' => 'required',
            'komisi_toko' => 'required',
            'komisi_hunter' => 'nullable',
            'tanggal_komisi' => 'required',
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



        if ($request->has('id_penitip')  && $request->id_penitip != null && $request->has('id_penitip') != $Komisi->id_penitip) {
            //ini kurangi yang lama dulu
            $KomisiLama = Penitip::find($Komisi->id_penitip);
            $KomisiLama->saldo = ($KomisiLama->saldo - $Komisi->komisi_penitip);
            $totalLama =  $Komisi->komisi_penitip;
            if ($request->has('bonus_penitip')  && $request->bonus_penitip != null) {
                $KomisiLama->saldo = $KomisiLama->saldo - $Komisi->bonus_penitip;
                $totalLama = $totalLama +  $Komisi->bonus_penitip;
            }
            $KomisiLama->update();
            $currentDate = Carbon::now();
            $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->first();
            if (is_null($DataPenjualan)) {
                $StoreTambah['id_penitip'] = $request->id_penitip;
                $StoreTambah['total'] = 0;
                $StoreTambah['month'] = $currentDate->toDateString();
                $StoreTambah['bonus_pendapatan'] = 0;
                Detail_Pendapatan::create($StoreTambah);
            } else {
                $StoreTambah['id_penitip'] = $request->id_penitip;
                $StoreTambah['total'] = $DataPenjualan->total - $totalLama;
                $DataPenjualan->update($StoreTambah);
            }
            //ini tambah yang baru ( ganti penitip )
            $Penitip = Penitip::find($request->id_penitip);
            $Penitip->saldo = $Penitip->saldo + $updateData['komisi_penitip'];
            $total =  $updateData['komisi_penitip'];
            if ($request->has('bonus_penitip')  && $request->bonus_penitip != null) {
                $Penitip->saldo = $Penitip->saldo +  $updateData['bonus_penitip'];
                $total = $total +  $updateData['bonus_penitip'];
            }
            $Penitip->update();
            $currentDate = Carbon::now();
            $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->first();
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
        } else {
            $Penitip = Penitip::find($Komisi->id_penitip);
            $Penitip->saldo = ($Penitip->saldo - $Komisi->komisi_penitip) + $updateData['komisi_penitip'];
            $total =  $updateData['komisi_penitip'];
            if ($request->has('bonus_penitip')  && $request->bonus_penitip != null) {
                $Penitip->saldo = ($Penitip->saldo - $Komisi->bonus_penitip) +  $updateData['bonus_penitip'];
                $total = $total +  $updateData['bonus_penitip'];
            }
            $Penitip->update();
            $currentDate = Carbon::now();
            $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->first();
            if (is_null($DataPenjualan)) {
                $StoreTambah['id_penitip'] = $request->id_penitip;
                $StoreTambah['total'] = $total;
                $StoreTambah['month'] = $currentDate->toDateString();
                $StoreTambah['bonus_pendapatan'] = 0;
                Detail_Pendapatan::create($StoreTambah);
            } else {
                $StoreTambah['id_penitip'] = $request->id_penitip;
                $StoreTambah['total'] = ($DataPenjualan->total - ($Komisi->komisi_penitip + $Komisi->bonus_penitip)) + $total;
                $DataPenjualan->update($StoreTambah);
            }
        }
        $Komisi->update($updateData);
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
        //Kurangi Saldo Penitip
        $KomisiLama = Penitip::find($Komisi->id_penitip);
        $KomisiLama->saldo = ($KomisiLama->saldo - $Komisi->komisi_penitip);
        $totalLama =  $Komisi->komisi_penitip;
        if ($Komisi->bonus_penitip != null) {
            $KomisiLama->saldo = $KomisiLama->saldo - $Komisi->bonus_penitip;
            $totalLama = $totalLama +  $Komisi->bonus_penitip;
        }
        $KomisiLama->update();
        $currentDate = Carbon::now();
        $DataPenjualan = Detail_Pendapatan::whereMonth('month', $currentDate->month())->first();
        if (is_null($DataPenjualan)) {
            $StoreTambah['id_penitip'] = $Komisi->id_penitip;
            $StoreTambah['total'] = 0;
            $StoreTambah['month'] = $currentDate->toDateString();
            $StoreTambah['bonus_pendapatan'] = 0;
            Detail_Pendapatan::create($StoreTambah);
        } else {
            $StoreTambah['id_penitip'] = $Komisi->id_penitip;
            $StoreTambah['total'] = $DataPenjualan->total - $totalLama;
            $DataPenjualan->update($StoreTambah);
        }

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

    public function laporanKomisi(Request $request)
    {
        $query = Komisi::query()
            ->join('penitipan__barangs', 'komisis.id_barang', '=', 'penitipan__barangs.id_barang')
            ->select(
                'komisis.id_komisi',
                'komisis.id_barang',
                'penitipan__barangs.nama_barang',
                'penitipan__barangs.harga_barang',
                'penitipan__barangs.tanggal_penitipan',
                'penitipan__barangs.tanggal_laku',
                'komisis.komisi_hunter',
                'komisis.komisi_toko',
                'komisis.bonus_penitip'
            );

        if ($request->has('search') && $request->search != '') {
            $query->where('penitipan__barangs.nama_barang', 'like', '%' . $request->search . '%');
            // Bisa juga filter berdasarkan id_komisi atau kolom lain jika ingin
        }

        $perPage = $request->query('per_page', 10); // default 10
        $data = $query->paginate($perPage);

        return response([
            'message' => 'Laporan Komisi Retrieved',
            'data' => $data
        ], 200);
    }
}