<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_jabatan', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Jabatan = $query->paginate($perPage);

        return response([
            'message' => 'All Jabatan Retrieved',
            'data' => $Jabatan
        ], 200);
    }
    public function getData()
    {
        $data = Jabatan::get();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function showJabatanWithPegawaiByJabatanId(string $id)
    {
        $Jabatan = Jabatan::with('Pegawai')->find($id);

        if ($Jabatan) {
            return response([
                'message' => 'Jabatan Found',
                'data' => $Jabatan
            ], 200);
        }

        return response([
            'message' => 'Jabatan Not Found',
            'data' => null
        ], 404);
    }
    public function showJabatanWithPegawai()
    {
        $data = Jabatan::with('Pegawai')->get();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_jabatan' => 'required',
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

        $lastId = Jabatan::latest('Id_jabatan')->first();
        $newId = $lastId ? 'J-' . str_pad((int) substr($lastId->Id_jabatan, 2) + 1, 3, '0', STR_PAD_LEFT) : 'J-001';
        $storeData['Id_jabatan'] = $newId;

        $Jabatan = Jabatan::create($storeData);
        return response([
            'message' => 'Penitipan Barang Added Successfully',
            'data' => $Jabatan,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Jabatan = Jabatan::find($id);

        if ($Jabatan) {
            return response([
                'message' => 'Jabatan Found',
                'data' => $Jabatan
            ], 200);
        }

        return response([
            'message' => 'Jabatan Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Jabatan = Jabatan::find($id);
        if (is_null($Jabatan)) {
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_jabatan' => 'required',
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

        $Jabatan->update($updateData);

        return response([
            'message' => 'Jabatan Updated Successfully',
            'data' => $Jabatan,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Jabatan = Jabatan::find($id);

        if (is_null($Jabatan)) {
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ], 404);
        }

        if ($Jabatan->delete()) {
            return response([
                'message' => 'Jabatan Deleted Successfully',
                'data' => $Jabatan,
            ], 200);
        }

        return response([
            'message' => 'Delete Jabatan Failed',
            'data' => null,
        ], 400);
    }
}
