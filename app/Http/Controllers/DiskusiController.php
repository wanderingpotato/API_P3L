<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DiskusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Diskusi::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('id_Diskusi', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Diskusi = $query->paginate($perPage);


        return response([
            'message' => 'All Diskusi Retrieved',
            'data' => $Diskusi
        ], 200);
    }
    public function getData()
    {
        $data = Diskusi::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    public function getDataByPenitipId()
    {
        $idUser = Auth::id();
        $user = Penitip::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Diskusi::where('Id_penitip', $idUser)->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }
    public function getDataByPembeliId()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Diskusi::where('Id_Pembeli', $idUser)->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }
    public function getDataByPegawaiId()
    {
        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        $data = Diskusi::where('Id_Pegawai', $idUser)->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }
    public function getDataByBarangId($id)
    {
        $data = Diskusi::where('Id_Barang', $id)->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Booking Data Found',
                'data' => null
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_Pembeli' => 'nullable',
            'Id_Penitip' => 'nullable',
            'Id_Pegawai' => 'nullable',
            'Id_Barang' => 'required',
            'Title' => 'required',
            'Deskripsi' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Diskusi::latest('Id_diskusi')->first();
        $newId = $lastId ? 'DS' . str_pad((int) substr($lastId->Id_diskusi, 1) + 1, 3, '0', STR_PAD_LEFT) : 'DS-001';
        $storeData['Id_diskusi'] = $newId;

        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            $user = Penitip::find($idUser);
            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
            $storeData['Id_penitip'] = $user->Id_penitip;
        } else {
            $storeData['Id_Pembeli'] = $user->Id_Pembeli;
        }

        $Diskusi = Diskusi::create($storeData);
        return response([
            'message' => 'Diskusi Added Successfully',
            'data' => $Diskusi,
        ], 200);
    }

    public function storeDashboard(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_Pembeli' => 'nullable',
            'Id_Penitip' => 'nullable',
            'Id_Pegawai' => 'nullable',
            'Id_Barang' => 'required',
            'Title' => 'required',
            'Deskripsi' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Diskusi::latest('Id_diskusi')->first();
        $newId = $lastId ? 'DS' . str_pad((int) substr($lastId->Id_diskusi, 1) + 1, 3, '0', STR_PAD_LEFT) : 'DS-001';
        $storeData['Id_diskusi'] = $newId;

        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        if ($user->Id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }


        $Diskusi = Diskusi::create($storeData);
        return response([
            'message' => 'Diskusi Added Successfully',
            'data' => $Diskusi,
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Diskusi = Diskusi::find($id);

        if ($Diskusi) {
            return response([
                'message' => 'Diskusi Found',
                'data' => $Diskusi
            ], 200);
        }

        return response([
            'message' => 'Diskusi Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Diskusi = Diskusi::find($id);
        if (is_null($Diskusi)) {
            return response([
                'message' => 'Diskusi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_Pembeli' => 'nullable',
            'Id_Penitip' => 'nullable',
            'Id_Pegawai' => 'nullable',
            'Id_Barang' => 'required',
            'Title' => 'required',
            'Deskripsi' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            $user = Penitip::find($idUser);
            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
            $updateData['Id_penitip'] = $user->Id_penitip;
        } else {
            $updateData['Id_Pembeli'] = $user->Id_Pembeli;
        }

        $Diskusi->update($updateData);

        return response([
            'message' => 'Diskusi Updated Successfully',
            'data' => $Diskusi,
        ], 200);
    }

    public function updateDashboard(Request $request, string $id)
    {
        $Diskusi = Diskusi::find($id);
        if (is_null($Diskusi)) {
            return response([
                'message' => 'Diskusi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_Pembeli' => 'nullable',
            'Id_Penitip' => 'nullable',
            'Id_Pegawai' => 'nullable',
            'Id_Barang' => 'required',
            'Title' => 'required',
            'Deskripsi' => 'required',
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
        if ($user->Id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $Diskusi->update($updateData);


        return response([
            'message' => 'Diskusi Updated Successfully',
            'data' => $Diskusi,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Diskusi = Diskusi::find($id);

        if (is_null($Diskusi)) {
            return response([
                'message' => 'Diskusi Not Found',
                'data' => null
            ], 404);
        }

        if ($Diskusi->delete()) {
            return response([
                'message' => 'Diskusi Deleted Successfully',
                'data' => $Diskusi,
            ], 200);
        }

        return response([
            'message' => 'Delete Diskusi Failed',
            'data' => null,
        ], 400);
    }
}
