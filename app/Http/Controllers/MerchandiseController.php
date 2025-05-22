<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MerchandiseController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchandise::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Merchandise = $query->paginate($perPage);

        return response([
            'message' => 'All Merchandise Retrieved',
            'data' => $Merchandise
        ], 200);
    }
    public function getData()
    {
        $data = Merchandise::get();

        return response([
            'message' => 'All Merchandise Retrieved',
            'data' => $data
        ], 200);
    }
    public function showMerchandisebyPenitip($id)
    {
        $user = Penitip::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Merchandise = Merchandise::where('id_penitip', $user->id_penitip)->get();
        return response([
            'message' => 'Merchandise of ' . $user->name . ' Retrieved',
            'data' => $Merchandise
        ], 200);
    }
    public function showMerchandisebyPembeli($id)
    {
        $user = Pembeli::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $Merchandise = Merchandise::where('id_Pembeli', $user->id_Pembeli)->get();
        return response([
            'message' => 'Merchandise of ' . $user->name . ' Retrieved',
            'data' => $Merchandise
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
            'nama' => 'required',
            'poin' => 'required',
            'kategori' => 'required',
            'stock' => 'required',
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
        if ($user->id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $lastId = Merchandise::latest('id_merchandise')->first();
        $newId = $lastId ? 'M-' . str_pad((int) substr($lastId->id_merchandise, 2) + 1, 3, '0', STR_PAD_LEFT) : 'M-001';
        $storeData['id_merchandise'] = $newId;

        $Merchandise = Merchandise::create($storeData);
        return response([
            'message' => 'Merchandise Added Successfully',
            'data' => $Merchandise,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Merchandise = Merchandise::find($id);

        if ($Merchandise) {
            return response([
                'message' => 'Merchandise Found',
                'data' => $Merchandise
            ], 200);
        }

        return response([
            'message' => 'Merchandise Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Merchandise = Merchandise::find($id);
        if (is_null($Merchandise)) {
            return response([
                'message' => 'Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'poin' => 'required',
            'kategori' => 'required',
            'stock' => 'required',
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
        if ($user->id_jabatan == 'J-001') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $Merchandise->update($updateData);

        return response([
            'message' => 'Merchandise Updated Successfully',
            'data' => $Merchandise,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Merchandise = Merchandise::find($id);

        if (is_null($Merchandise)) {
            return response([
                'message' => 'Merchandise Not Found',
                'data' => null
            ], 404);
        }

        if ($Merchandise->delete()) {
            return response([
                'message' => 'Merchandise Deleted Successfully',
                'data' => $Merchandise,
            ], 200);
        }

        return response([
            'message' => 'Delete Merchandise Failed',
            'data' => null,
        ], 400);
    }
}
