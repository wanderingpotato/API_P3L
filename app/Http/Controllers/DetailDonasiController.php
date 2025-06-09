<?php

namespace App\Http\Controllers;

use App\Models\Detail_Donasi;
use App\Models\Donasi;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Simpan detail donasi
        $DetailDonasi = Detail_Donasi::create($storeData);

        $barang = Penitipan_Barang::find($storeData['id_barang']);
        if ($barang) {
            $barang->status = "DiDonasikan";
            $barang->save();
        }

        // Update donasi terkait
        $donasi = Donasi::find($storeData['id_donasi']);
        if ($donasi) {
            $donasi->tanggal_diberikan = now();  // pakai tanggal sekarang
            $donasi->konfirmasi = 1;
            $donasi->save();
        }

        return response([
            'message' => 'DetailDonasi Added Successfully and Donasi updated',
            'data' => $DetailDonasi,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $DetailDonasi = Detail_Donasi::where('id_donasi', $id)->get();

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

    public function laporanDonasiBarang(Request $request)
    {
        $query = DB::table('detail__donasis as dd')
            ->join('penitipan__barangs as b', 'dd.id_barang', '=', 'b.id_barang')
            ->join('penitips as p', 'dd.id_penitip', '=', 'p.id_penitip')
            ->join('donasis as d', 'dd.id_donasi', '=', 'd.id_donasi')
            ->join('organisasis as o', 'd.id_organisasi', '=', 'o.id_organisasi')
            ->select(
                'b.id_barang as kode_produk',
                'b.nama_barang as nama_produk',
                'p.id_penitip',
                'p.name as nama_penitip',
                'd.tanggal_diberikan as tanggal_donasi',
                'o.name as organisasi',
                'd.nama_penerima'
            );

        // Filter pencarian (nama produk / penitip)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('b.nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('p.name', 'like', '%' . $search . '%');
            });
        }

        // Filter organisasi
        if ($request->has('organisasi') && $request->organisasi != '') {
            $query->where('o.name', 'like', '%' . $request->organisasi . '%');
        }

        // Filter tanggal donasi
        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('d.tanggal_diberikan', [$request->from, $request->to]);
        }

        $perPage = $request->query('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'message' => 'Laporan Donasi Barang Retrieved',
            'data' => $data
        ]);
    }
}