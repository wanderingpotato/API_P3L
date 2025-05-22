<?php

namespace App\Http\Controllers;

use App\Models\Klaim_Merchandise;
use App\Models\Merchandise;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KlaimMerchandiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Klaim_Merchandise::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('id_klaim', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Klaim_Merchandise = $query->paginate($perPage);


        return response([
            'message' => 'All Klaim Merchandise Retrieved',
            'data' => $Klaim_Merchandise
        ], 200);
    }
    public function getData()
    {
        $data = Klaim_Merchandise::all();

        return response([
            'message' => 'All Klaim Merchandise Retrieved',
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
        $data = Klaim_Merchandise::where('Id_penitip', $idUser)->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Klaim Merchandise Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Klaim Merchandise Data Found',
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
        $data = Klaim_Merchandise::where('Id_Pembeli', $idUser)->get();
        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Klaim Merchandise Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'No Klaim Merchandise Data Found',
                'data' => null
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function countKlaim()
    {
        $count = Klaim_Merchandise::count();
        return response([
            'message' => 'Count Klaim Merchandise Retrieved Successfully',
            'count' => $count
        ], 200);
    }
    public function countKlaimByPenitip()
    {
        $idUser = Auth::id();
        $user = Penitip::find($idUser);
        if (is_null($user)) {
            return response(['message' => 'User Not Found'], 404);
        }
        $count = Klaim_Merchandise::where('id_penitip', $idUser)->count();
        return response([
            'message' => 'Count Klaim Merchandise Retrieved Successfully',
            'count' => $count
        ], 200);
    }
    public function countKlaimByPembeli()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response(['message' => 'User Not Found'], 404);
        }
        $count = Klaim_Merchandise::where('id_pembeli', $idUser)->count();
        return response([
            'message' => 'Count Klaim Merchandise Retrieved Successfully',
            'count' => $count
        ], 200);
    }
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_merchandise' => 'required',
            'jumlah' => 'required',
            'tanggal_ambil' => 'required',
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Klaim_Merchandise::latest('id_klaim')->first();
        $newId = $lastId ? 'KM' . str_pad((int) substr($lastId->id_klaim, 1) + 1, 3, '0', STR_PAD_LEFT) : 'KM-001';
        $storeData['id_klaim'] = $newId;

        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            $user = Penitip::find($idUser);
            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
            $storeData['id_penitip'] = $user->id_penitip;
        } else {
            $storeData['id_pembeli'] = $user->id_pembeli;
        }

        $Klaim_Merchandise = Klaim_Merchandise::create($storeData);
        $pointBarang = Merchandise::where('id_merchandise', $storeData['id_merchandise'])->get();
        $updateData['poin'] = $user->poin - ($pointBarang->Poin * $storeData['jumlah']);
        $user->update($updateData);
        $updateBarang['stock'] = $pointBarang->Stock - $storeData['jumlah'];
        $pointBarang->update($updateBarang);
        return response([
            'message' => 'Klaim Merchandise Added Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }

    public function storeDashboard(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_pembeli' => 'nullable',
            'id_penitip' => 'nullable',
            'id_merchandise' => 'required',
            'jumlah' => 'required',
            'tanggal_ambil' => 'required',
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Klaim_Merchandise::latest('id_klaim')->first();
        $newId = $lastId ? 'KM' . str_pad((int) substr($lastId->id_klaim, 1) + 1, 3, '0', STR_PAD_LEFT) : 'KM-001';
        $storeData['id_klaim'] = $newId;

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


        $Klaim_Merchandise = Klaim_Merchandise::create($storeData);
        $pointBarang = Merchandise::where('id_merchandise', $storeData['id_merchandise'])->get();
        $userP = Pembeli::find($storeData['id_pembeli']);
        if (is_null($userP)) {
            $userP = Penitip::find($storeData['id_penitip']);
            if (is_null($userP)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $updateData['poin'] = $userP->poin - ($pointBarang->poin * $storeData['jumlah']);
        $userP->update($updateData);

        $updateBarang['stock'] = $pointBarang->stock - $storeData['jumlah'];
        $pointBarang->update($updateBarang);
        return response([
            'message' => 'Klaim Merchandise Added Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);

        if ($Klaim_Merchandise) {
            return response([
                'message' => 'Klaim Merchandise Found',
                'data' => $Klaim_Merchandise
            ], 200);
        }

        return response([
            'message' => 'Klaim Merchandise Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);
        if (is_null($Klaim_Merchandise)) {
            return response([
                'message' => 'Klaim Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_merchandise' => 'nullable',
            'jumlah' => 'required',
            'tanggal_ambil' => 'required',
            'status' => 'required',
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
            $updateData['id_penitip'] = $user->id_penitip;
        } else {
            $updateData['id_Pembeli'] = $user->id_Pembeli;
        }

        $Klaim_Merchandise->update($updateData);

        $pointBarang = Merchandise::where('id_merchandise', $Klaim_Merchandise->id_merchandise)->get();
        if ($request->has('id_merchandise')  && $request->id_merchandise != null) {
            $updateData['id_merchandise'] = $request->id_merchandise;
            $pointBarang1 = Merchandise::where('id_merchandise', $updateData['id_merchandise'])->get();
            $updatePoint['poin'] = ($user->poin + ($pointBarang->poin * $Klaim_Merchandise->jumlah)) - ($pointBarang1->poin * $updateData['jumlah']);
            $updateBarang['stock'] = ($pointBarang->stock + $Klaim_Merchandise->jumlah);
            $updateBarang['stock'] = $pointBarang1->stock - $updateData['jumlah'];
        } else {
            $updatePoint['poin'] = ($user->poin + ($pointBarang->poin * $Klaim_Merchandise->jumlah)) - ($pointBarang->poin * $updateData['jumlah']);
            $updateBarang['stock'] = ($pointBarang->stock + $Klaim_Merchandise->jumlah) - $updateData['jumlah'];
        }
        $user->update($updatePoint);
        $pointBarang->update($updateBarang);

        return response([
            'message' => 'Klaim Merchandise Updated Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }
    public function UpdateStatus(Request $request, string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);
        if (is_null($Klaim_Merchandise)) {
            return response([
                'message' => 'Klaim Merchandise Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_merchandise' => 'nullable',
            'jumlah' => 'nullable',
            'tanggal_ambil' => 'nullable',
            'status' => 'required',
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
        if ($user->id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $Klaim_Merchandise->update($updateData);
        return response([
            'message' => 'Klaim Merchandise Updated Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }

    public function updateDashboard(Request $request, string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);
        if (is_null($Klaim_Merchandise)) {
            return response([
                'message' => 'Klaim Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_merchandise' => 'nullable',
            'jumlah' => 'required',
            'tanggal_ambil' => 'required',
            'status' => 'required',
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
        if ($user->id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }

        $Klaim_Merchandise->update($updateData);
        $userP = Pembeli::find($updateData['id_Pembeli']);
        if (is_null($userP)) {
            $userP = Penitip::find($updateData['id_penitip']);
            if (is_null($userP)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $pointBarang = Merchandise::where('id_merchandise', $Klaim_Merchandise->id_merchandise)->get();
        if ($request->has('id_merchandise')  && $request->id_merchandise != null) {
            $updateData['id_merchandise'] = $request->id_merchandise;
            $pointBarang1 = Merchandise::where('id_merchandise', $updateData['id_merchandise'])->get();
            $updatePoint['poin'] = ($userP->poin + ($pointBarang->poin * $Klaim_Merchandise->jumlah)) - ($pointBarang1->poin * $updateData['jumlah']);
            $updateBarang['stock'] = ($pointBarang->stock + $Klaim_Merchandise->jumlah);
            $updateBarang['stock'] = $pointBarang1->stock - $updateData['jumlah'];
        } else {
            $updatePoint['poin'] = ($userP->poin + ($pointBarang->poin * $Klaim_Merchandise->jumlah)) - ($pointBarang->poin * $updateData['jumlah']);
            $updateBarang['stock'] = ($pointBarang->stock + $Klaim_Merchandise->jumlah) - $updateData['jumlah'];
        }
        $userP->update($updatePoint);
        $pointBarang->update($updateBarang);

        return response([
            'message' => 'Klaim Merchandise Updated Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);

        if (is_null($Klaim_Merchandise)) {
            return response([
                'message' => 'Klaim Merchandise Not Found',
                'data' => null
            ], 404);
        }
        $pointBarang = Merchandise::where('id_merchandise', $Klaim_Merchandise->id_merchandise)->get();
        $userP = Pembeli::find($Klaim_Merchandise->id_pembeli);
        if (is_null($userP)) {
            $userP = Penitip::find($Klaim_Merchandise->id_penitip);
            if (is_null($userP)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }

        $updatePoint['poin'] = ($userP->poin + ($pointBarang->poin * $Klaim_Merchandise->jumlah));
        $updateBarang['stock'] = ($pointBarang->stock + $Klaim_Merchandise->jumlah);

        $userP->update($updatePoint);
        $pointBarang->update($updateBarang);

        if ($Klaim_Merchandise->delete()) {
            return response([
                'message' => 'Klaim_Merchandise Deleted Successfully',
                'data' => $Klaim_Merchandise,
            ], 200);
        }

        return response([
            'message' => 'Delete Klaim_Merchandise Failed',
            'data' => null,
        ], 400);
    }
}
