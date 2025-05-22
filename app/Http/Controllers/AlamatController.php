<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Pegawai;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Alamat::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Alamat = $query->paginate($perPage);

        return response([
            'message' => 'All Alamat Retrieved',
            'data' => $Alamat
        ], 200);
    }
    public function getData()
    {
        $data = Alamat::get();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    public function showAlamatbyPembeli($id)
    {
        $user = Pembeli::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Alamat = Alamat::where('id_pembeli', $user->id_pembeli)->get();
        return response([
            'message' => 'Penitipan Barang of ' . $user->name . ' Retrieved',
            'data' => $Alamat
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
            'id_pembeli' => 'required',
            'no_telp' => 'required',
            'title' => 'required',
            'default' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
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
        if ($user->Id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $lastId = Alamat::latest('id_alamat')->first();
        $newId = $lastId ? 'A-' . str_pad((int) substr($lastId->id_alamat, 2) + 1, 4, '0', STR_PAD_LEFT) : 'A-0001';
        $storeData['id_alamat'] = $newId;

        $Alamat = Alamat::create($storeData);
        return response([
            'message' => 'Penitipan Barang Added Successfully',
            'data' => $Alamat,
        ], 200);
    }

    public function AddAlamatPembeli(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'no_telp' => 'required',
            'title' => 'required',
            // 'default'=> 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser =  Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $storeData['id_pembeli'] = $user->id_pembeli;
        $lastId = Alamat::latest('id_alamat')->first();
        $newId = $lastId ? 'A-' . str_pad((int) substr($lastId->id_alamat, 2) + 1, 4, '0', STR_PAD_LEFT) : 'A-0001';
        $storeData['id_alamat'] = $newId;

        $Alamat = Alamat::create($storeData);
        return response([
            'message' => 'Penitipan Barang Added Successfully',
            'data' => $Alamat,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Alamat = Alamat::find($id);

        if ($Alamat) {
            return response([
                'message' => 'Alamat Found',
                'data' => $Alamat
            ], 200);
        }

        return response([
            'message' => 'Alamat Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Alamat = Alamat::find($id);
        if (is_null($Alamat)) {
            return response([
                'message' => 'Alamat Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_pembeli' => 'required',
            'no_telp' => 'required',
            'title' => 'required',
            'default' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
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
        if ($user->Id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $Alamat->update($updateData);

        return response([
            'message' => 'Alamat Updated Successfully',
            'data' => $Alamat,
        ], 200);
    }
    public function EditAlamatPembeli(Request $request, string $id)
    {
        $Alamat = Alamat::find($id);
        if (is_null($Alamat)) {
            return response([
                'message' => 'Alamat Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'no_telp' => 'required',
            'title' => 'required',
            'default' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }

        $Alamat->update($updateData);

        return response([
            'message' => 'Alamat Updated Successfully',
            'data' => $Alamat,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Alamat = Alamat::find($id);

        if (is_null($Alamat)) {
            return response([
                'message' => 'Alamat Not Found',
                'data' => null
            ], 404);
        }

        if ($Alamat->delete()) {
            return response([
                'message' => 'Alamat Deleted Successfully',
                'data' => $Alamat,
            ], 200);
        }

        return response([
            'message' => 'Delete Alamat Failed',
            'data' => null,
        ], 400);
    }
}
