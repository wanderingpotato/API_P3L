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
        $query = Klaim_Merchandise::all();
        if ($request->has('search') && $request->search != '') {
            $query->where('id_Klaim_Merchandise', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $Klaim_Merchandise = $query->paginate($perPage);


        return response([
            'message' => 'All Klaim_Merchandise Retrieved',
            'data' => $Klaim_Merchandise
        ], 200);
    }
    public function getData()
    {
        $data = Klaim_Merchandise::all();

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
        $data = Klaim_Merchandise::where('Id_penitip', $idUser)->get();
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
        $data = Klaim_Merchandise::where('Id_Pembeli', $idUser)->get();
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
    public function countKlaim()
    {
        $count = Klaim_Merchandise::count();
        return response([
            'message' => 'Count Retrieved Successfully',
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
        $count = Klaim_Merchandise::where('Id_penitip', $idUser)->count();
        return response([
            'message' => 'Count Retrieved Successfully',
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
        $count = Klaim_Merchandise::where('Id_Pembeli', $idUser)->count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_merchandise' => 'required',
            'Jumlah' => 'required',
            'Tanggal_ambil' => 'required',
            'Status' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Klaim_Merchandise::latest('Id_klaim')->first();
        $newId = $lastId ? 'KM' . str_pad((int) substr($lastId->Id_klaim, 1) + 1, 3, '0', STR_PAD_LEFT) : 'KM-001';
        $storeData['Id_klaim'] = $newId;

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

        $Klaim_Merchandise = Klaim_Merchandise::create($storeData);
        $pointBarang = Merchandise::where('Id_merchandise', $storeData['Id_merchandise'])->get();
        $updateData['Poin'] = $user->poin - ($pointBarang->Poin * $storeData['Jumlah']);
        $user->update($updateData);
        $updateBarang['Stock'] = $pointBarang->Stock - $storeData['Jumlah'];
        $pointBarang->update($updateBarang);
        return response([
            'message' => 'Klaim_Merchandise Added Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }

    public function storeDashboard(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'Id_Pembeli' => 'nullable',
            'Id_penitip' => 'nullable',
            'Id_merchandise' => 'required',
            'Jumlah' => 'required',
            'Tanggal_ambil' => 'required',
            'Status' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $lastId = Klaim_Merchandise::latest('Id_klaim')->first();
        $newId = $lastId ? 'KM' . str_pad((int) substr($lastId->Id_klaim, 1) + 1, 3, '0', STR_PAD_LEFT) : 'KM-001';
        $storeData['Id_klaim'] = $newId;

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


        $Klaim_Merchandise = Klaim_Merchandise::create($storeData);
        $pointBarang = Merchandise::where('Id_merchandise', $storeData['Id_merchandise'])->get();
        $userP = Pembeli::find($storeData['Id_Pembeli']);
        if (is_null($userP)) {
            $userP = Penitip::find($storeData['Id_penitip']);
            if (is_null($userP)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $updateData['Poin'] = $userP->poin - ($pointBarang->Poin * $storeData['Jumlah']);
        $userP->update($updateData);

        $updateBarang['Stock'] = $pointBarang->Stock - $storeData['Jumlah'];
        $pointBarang->update($updateBarang);
        return response([
            'message' => 'Klaim_Merchandise Added Successfully',
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
                'message' => 'Klaim_Merchandise Found',
                'data' => $Klaim_Merchandise
            ], 200);
        }

        return response([
            'message' => 'Klaim_Merchandise Not Found',
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
                'message' => 'Klaim_Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_merchandise' => 'nullable',
            'Jumlah' => 'required',
            'Tanggal_ambil' => 'required',
            'Status' => 'required',
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

        $Klaim_Merchandise->update($updateData);

        $pointBarang = Merchandise::where('Id_merchandise', $Klaim_Merchandise->Id_merchandise)->get();
        if ($request->has('Id_merchandise')  && $request->Id_merchandise != null) {
            $updateData['Id_merchandise'] = $request->Id_merchandise;
            $pointBarang1 = Merchandise::where('Id_merchandise', $updateData['Id_merchandise'])->get();
            $updatePoint['Poin'] = ($user->poin + ($pointBarang->Poin * $Klaim_Merchandise->Jumlah)) - ($pointBarang1->Poin * $updateData['Jumlah']);
            $updateBarang['Stock'] = ($pointBarang->Stock + $Klaim_Merchandise->Jumlah);
            $updateBarang['Stock'] = $pointBarang1->Stock - $updateData['Jumlah'];
        } else {
            $updatePoint['Poin'] = ($user->poin + ($pointBarang->Poin * $Klaim_Merchandise->Jumlah)) - ($pointBarang->Poin * $updateData['Jumlah']);
            $updateBarang['Stock'] = ($pointBarang->Stock + $Klaim_Merchandise->Jumlah) - $updateData['Jumlah'];
        }
        $user->update($updatePoint);
        $pointBarang->update($updateBarang);

        return response([
            'message' => 'Klaim_Merchandise Updated Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }
    public function UpdateStatus(Request $request, string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);
        if (is_null($Klaim_Merchandise)) {
            return response([
                'message' => 'Klaim_Merchandise Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_merchandise' => 'nullable',
            'Jumlah' => 'nullable',
            'Tanggal_ambil' => 'nullable',
            'Status' => 'required',
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
        $Klaim_Merchandise->update($updateData);
        return response([
            'message' => 'Klaim_Merchandise Updated Successfully',
            'data' => $Klaim_Merchandise,
        ], 200);
    }

    public function updateDashboard(Request $request, string $id)
    {
        $Klaim_Merchandise = Klaim_Merchandise::find($id);
        if (is_null($Klaim_Merchandise)) {
            return response([
                'message' => 'Klaim_Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'Id_merchandise' => 'nullable',
            'Jumlah' => 'required',
            'Tanggal_ambil' => 'required',
            'Status' => 'required',
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

        $Klaim_Merchandise->update($updateData);
        $userP = Pembeli::find($updateData['Id_Pembeli']);
        if (is_null($userP)) {
            $userP = Penitip::find($updateData['Id_penitip']);
            if (is_null($userP)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }
        $pointBarang = Merchandise::where('Id_merchandise', $Klaim_Merchandise->Id_merchandise)->get();
        if ($request->has('Id_merchandise')  && $request->Id_merchandise != null) {
            $updateData['Id_merchandise'] = $request->Id_merchandise;
            $pointBarang1 = Merchandise::where('Id_merchandise', $updateData['Id_merchandise'])->get();
            $updatePoint['Poin'] = ($userP->poin + ($pointBarang->Poin * $Klaim_Merchandise->Jumlah)) - ($pointBarang1->Poin * $updateData['Jumlah']);
            $updateBarang['Stock'] = ($pointBarang->Stock + $Klaim_Merchandise->Jumlah);
            $updateBarang['Stock'] = $pointBarang1->Stock - $updateData['Jumlah'];
        } else {
            $updatePoint['Poin'] = ($userP->poin + ($pointBarang->Poin * $Klaim_Merchandise->Jumlah)) - ($pointBarang->Poin * $updateData['Jumlah']);
            $updateBarang['Stock'] = ($pointBarang->Stock + $Klaim_Merchandise->Jumlah) - $updateData['Jumlah'];
        }
        $userP->update($updatePoint);
        $pointBarang->update($updateBarang);

        return response([
            'message' => 'Klaim_Merchandise Updated Successfully',
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
                'message' => 'Klaim_Merchandise Not Found',
                'data' => null
            ], 404);
        }
        $pointBarang = Merchandise::where('Id_merchandise', $Klaim_Merchandise->Id_merchandise)->get();
        $userP = Pembeli::find($Klaim_Merchandise->Id_Pembeli);
        if (is_null($userP)) {
            $userP = Penitip::find($Klaim_Merchandise->Id_penitip);
            if (is_null($userP)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
        }

        $updatePoint['Poin'] = ($userP->poin + ($pointBarang->Poin * $Klaim_Merchandise->Jumlah));
        $updateBarang['Stock'] = ($pointBarang->Stock + $Klaim_Merchandise->Jumlah);

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
