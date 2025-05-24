<?php

namespace App\Http\Controllers;

use App\Models\Detail_Pembelian;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PenitipanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penitipan_Barang::with('Kategori_Barang');
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $PenitipanBarang = $query->paginate($perPage);

        return response([
            'message' => 'All PenitipanBarang Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    public function getData()
    {
        $data = Penitipan_Barang::with('Kategori_Barang')->get();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }

    public function Updaterating(Request $request, $id)
    {
        $PenitipanBarang = Penitipan_Barang::find($id);
        if (is_null($PenitipanBarang)) {
            return response([
                'message' => 'PenitipanBarang Not Found',
                'data' => null
            ], 404);
        }
        $updateData = [
            "rating" => $request->input('rating'),
            "tanggal_rating" => now()
        ];
        $PenitipanBarang->update($updateData);
        $user = Penitip::find($PenitipanBarang->id_penitip);
        if (is_null($user)) {
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }
        $ratingBarang = Penitipan_Barang::where('id_penitip', $user->id_penitip)->where('status', 'DiBeli')
            ->where('rating', '!=', 0)->count();

        $updateData['Ratarating'] = ($user->Ratarating * ($ratingBarang - 1)) + ($request->input('rating') / $ratingBarang);

        $user->update($updateData);
        return response([
            'message' => 'rating updated successfully',
            'data' => $user
        ], 200);
    }
    public function showPenitipanBarangbyPenitip($id)
    {
        $user = Penitip::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $PenitipanBarang = Penitipan_Barang::where('id_penitip', $user->id_penitip)->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    public function getDataByPenitipId()
    {
        $idUser = Auth::id();
        $user = Penitip::find($idUser);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $PenitipanBarang = Penitipan_Barang::where('id_penitip', $user->id_penitip)->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    public function showPenitipanBarangbyPembeli($id)
    {
        $user = Pembeli::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $pembelianIds = $user->Pembelian()->pluck('id_pembelian');
        $barangIds = Detail_Pembelian::whereIn('id_pembelian', $pembelianIds)->pluck('id_barang');
        $PenitipanBarang = Penitipan_Barang::whereIn('id_barang', $barangIds)->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $PenitipanBarang
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_kategori' => 'required',
            'id_penitip' => 'required',
            'id_pegawai' => '',
            'nama_barang' => 'required',
            'di_perpanjang' => 'required',
            'diliver_here' => 'required',
            'hunter' => 'required',
            'status' => 'required',
            'harga_barang' => 'required',
            'rating' => '',
            'tanggal_penitipan' => 'required',
            'tanggal_kadaluarsa' => '',
            'batas_ambil' => '',
            'tanggal_laku' => '',
            'tanggal_rating' => '',
            'garansi' => '',
            'deskripsi' => '',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $tanggalMasuk = Carbon::parse($storeData['tanggal_penitipan']);
        $tanggalKadaluarsa = $tanggalMasuk->copy()->addDays(30);
        $storeData['tanggal_kadaluarsa'] = $tanggalMasuk->addDays(30)->toDateString();

        $batasAmbil = $tanggalKadaluarsa->copy()->addDays(7);
        $storeData['batas_ambil'] = $batasAmbil->toDateString();

        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response(['message' => 'User Not Found'], 403);
        }
        if ($user->id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $lastId = Penitipan_Barang::latest('id_barang')->first();
        $newId = $lastId ? 'PB-' . str_pad((int) substr($lastId->id_barang, 3) + 1, 4, '0', STR_PAD_LEFT) : 'PB-0001';
        $storeData['id_barang'] = $newId;
        // if ($request->hasFile('foto')) {
        //     $uploadFolder = 'FotoBarang';
        //     $image = $request->file('Foto_Barang');
        //     $image_uploaded_path = $image->store($uploadFolder, 'public');
        //     $uploadedImageResponse = basename($image_uploaded_path);

        //     $storeData['Foto_Barang'] = $uploadedImageResponse;
        // }
        $PenitipanBarang = Penitipan_Barang::create($storeData);

        return response([
            'message' => 'Penitipan Barang Added Successfully',
            'data' => $PenitipanBarang,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $PenitipanBarang = Penitipan_Barang::with(['Kategori_Barang'])->find($id);

        if ($PenitipanBarang) {
            return response([
                'message' => 'PenitipanBarang Found',
                'data' => $PenitipanBarang
            ], 200);
        }

        return response([
            'message' => 'PenitipanBarang Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $PenitipanBarang = Penitipan_Barang::find($id);
        if (is_null($PenitipanBarang)) {
            return response([
                'message' => 'PenitipanBarang Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_kategori' => 'required',
            'id_penitip' => 'required',
            'id_pegawai' => '',
            'nama_barang' => 'required',
            'di_perpanjang' => 'required',
            'diliver_here' => 'required',
            'hunter' => 'required',
            'status' => 'required',
            'harga_barang' => 'required',
            'rating' => '',
            'tanggal_penitipan' => 'required',
            'tanggal_kadaluarsa' => '',
            'batas_ambil' => '',
            'tanggal_laku' => '',
            'tanggal_rating' => '',
            'garansi' => '',
            'deskripsi' => '',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $tanggalMasuk = Carbon::parse($updateData['tanggal_penitipan']);
        $tanggalKadaluarsa = $tanggalMasuk->copy()->addDays(30);
        $updateData['tanggal_kadaluarsa'] = $tanggalKadaluarsa->toDateString();

        // Hitung batas_ambil = tanggal_kadaluarsa + 7 hari
        $batasAmbil = $tanggalKadaluarsa->copy()->addDays(7);
        $updateData['batas_ambil'] = $batasAmbil->toDateString();

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

        // if ($request->hasFile('foto')) {
        //     $uploadFolder = 'FotoBarang';
        //     $image = $request->file('Foto_Barang');
        //     $image_uploaded_path = $image->store($uploadFolder, 'public');
        //     $uploadedImageResponse = basename($image_uploaded_path);

        //     $updateData['Foto_Barang'] = $uploadedImageResponse;
        // }

        $PenitipanBarang->update($updateData);

        return response([
            'message' => 'PenitipanBarang Updated Successfully',
            'data' => $PenitipanBarang,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $PenitipanBarang = Penitipan_Barang::find($id);

        if (is_null($PenitipanBarang)) {
            return response([
                'message' => 'PenitipanBarang Not Found',
                'data' => null
            ], 404);
        }

        if ($PenitipanBarang->delete()) {
            return response([
                'message' => 'PenitipanBarang Deleted Successfully',
                'data' => $PenitipanBarang,
            ], 200);
        }

        return response([
            'message' => 'Delete PenitipanBarang Failed',
            'data' => null,
        ], 400);
    }
}
