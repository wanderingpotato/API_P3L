<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PembeliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $registrationData = $request->all();


        $validate = Validator::make($registrationData, [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:Pembelis',
            'password' => 'required|min:8',
            'no_telp' => 'required|min:10',
            'username' => 'required|unique:Pembelis',
        ]);
        $registrationData['poin'] = 0;

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }
        $registrationData['password'] = bcrypt($request->password);

        $user = Pembeli::create($registrationData);

        //buat keranjang biar bisa nyimpen data keranjangnya
        $lastId = Pembelian::latest('id_pembelian')->first();
        $newId = $lastId ? 'PM-' . str_pad((int) substr($lastId->id_pembelian, 3) + 1, 4, '0', STR_PAD_LEFT) : 'PM-0001';
        $storeData['id_pembelian'] = $newId;
        $storeData['id_pembeli'] = $user->id_pembeli;
        $storeData['dilivery'] = 0;
        $storeData['status'] = "Keranjang";
        $storeData['status_pengiriman'] = "Keranjang";
        $storeData['point_yg_didapat'] = 0;
        $storeData['point_current'] = 0;
        $storeData['point_digunakan'] = 0;
        $storeData['potongan_harga'] = 0;
        $storeData['harga_barang'] = 0;
        $storeData['ongkir'] = 0;
        $storeData['batas_waktu'] = "2000-01-01";
        $storeData['tanggal_pembelian'] = "2000-01-01";
        $storeData['tanggal_lunas'] = "2000-01-01";
        $storeData['tanggal_pengiriman-pengambilan'] = "2000-01-01";
        $storeData['bukti_pembayaran'] = "null";
        Pembelian::create($storeData);


        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'username' => 'required',
            'password' => 'required|min:8',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $user = Pembeli::where('username', $loginData['username'])->first();
        if (!$user || !Hash::check($loginData['password'], $user->password)) {
            return response(['message' => 'Invalid username or password'], 401);
        }
        $token = $user->createToken('Auth Token')->plainTextToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logged out'
        ]);
    }
    /**
     * Display a listing of the resource.
     */



    public function index(Request $request)
    {
        $query = Pembeli::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $User = $query->paginate($perPage);


        return response([
            'message' => 'All User Retrieved',
            'data' => $User
        ], 200);
    }
    public function getData()
    {
        $data = Pembeli::all();

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
        $idUser = Auth::id();
        $userCheck = Pegawai::find($idUser);
        if (is_null($userCheck)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        if ($userCheck->Id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:Pembelis',
            'password' => 'required|min:8',
            'no_telp' => 'required|min:10',
            'username' => 'required|unique:Pembelis',
        ]);
        $registrationData['poin'] = 0;

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $storeData['password'] = bcrypt($request->password);

        if ($request->hasFile('foto')) {
            $uploadFolder = 'Profile';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            $storeData['foto'] = $uploadedImageResponse;
        }
        $user = Pembeli::create($storeData);
        $token = $user->createToken('Auth Token')->plainTextToken;
        return response([
            'message' => 'Register Success',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'User of ' . $user->name . ' Retrieved',
            'data' => $user
        ], 200);
    }
    public function poinUser()
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'User of ' . $user->name . ' Retrieved',
            'data' => $user->poin
        ], 200);
    }
     public function editFoto(Request $request)
    {
        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }
        $updateData = [];
        if ($request->hasFile('foto')) {
            $uploadFolder = 'Profile';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('Profile/' . $user->foto);
            $updateData['foto'] = $uploadedImageResponse;
        }
        $user->update($updateData);

        return response([
            'message' => 'user Updated Successfully',
            'data' => $user,
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Pembeli::find($id);
        if (is_null($user)) {
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }

        $updateData = [];
        if ($request->has('password')  && $request->password != null) {
            $updateData['password'] = bcrypt($request->password);
        }
        if ($request->has('email') && !is_null($request->email) && $request->email != $user->email) {
            $updateData['email'] =  $request->email;
        }
        if ($request->has('name')  && $request->name != null) {
            $updateData['name'] = $request->name;
        }
        if ($request->has('no_telp')  && $request->no_telp != null) {
            $updateData['no_telp'] = $request->no_telp;
        }
        if ($request->has('poin')  && $request->poin != null) {
            $updateData['poin'] = $request->poin;
        }
        if ($request->has('username')  && $request->username != null && $request->username != $user->username) {
            $updateData['username'] = $request->username;
        }
        $validate = Validator::make($updateData, [
            'name' => 'nullable',
            'email' => 'nullable|email:rfc,dns|unique:Pembelis',
            'password' => [
                'nullable',
                'string',
                'min:6',
            ],
            'no_telp' => 'nullable|min:10',
            'username' => 'nullable|unique:Pembelis',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $idUser = Auth::id();
        $userCheck = Pegawai::find($idUser);
        if (is_null($userCheck)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        if ($userCheck->Id_jabatan == 'J-003') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        if ($request->hasFile('foto')) {
            $uploadFolder = 'Profile';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('Profile/' . $user->foto);
            $updateData['foto'] = $uploadedImageResponse;
        }

        $user->update($updateData);

        return response([
            'message' => 'user Updated Successfully',
            'data' => $user,
        ], 200);
    }
    public function edit(Request $request)
    {
        $idUser = Auth::id();
        $user = Pembeli::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }
        $updateData = [];
        if ($request->has('password')  && $request->password != null) {
            $updateData['password'] = bcrypt($request->password);
        }
        if ($request->has('email') && !is_null($request->email) && $request->email != $user->email) {
            $updateData['email'] =  $request->email;
        }
        if ($request->has('name')  && $request->name != null) {
            $updateData['name'] = $request->name;
        }
        if ($request->has('no_telp')  && $request->no_telp != null) {
            $updateData['no_telp'] = $request->no_telp;
        }
        if ($request->has('username')  && $request->username != null && $request->username != $user->username) {
            $updateData['username'] = $request->username;
        }

        $validate = Validator::make($updateData, [
            'name' => 'nullable',
            'email' => 'nullable|email:rfc,dns|unique:Pembelis',
            'password' => [
                'nullable',
                'string',
                'min:6',
            ],
            'no_telp' => 'nullable|min:10',
            'username' => 'nullable|unique:Pembelis',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        if ($request->hasFile('foto')) {
            $uploadFolder = 'Profile';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            Storage::disk('public')->delete('Profile/' . $user->foto);
            $updateData['foto'] = $uploadedImageResponse;
        }
        $user->update($updateData);

        return response([
            'message' => 'user Updated Successfully',
            'data' => $user,
        ], 200);
    }
    public function resetPassword($id)
    {
        $User = Pembeli::find($id);

        if (is_null($User)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        $updateData['password'] = bcrypt("2000-01-05");
        $User->update($updateData);

        return response([
            'message' => 'user Updated Successfully',
            'data' => $User,
        ], 200);
    }
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response(['message' => __($status)], 200)
            : response(['error' => __($status)], 400);
    }
    public function countPembeli()
    {
        $count = Pembeli::count();
        return response([
            'message' => 'Count Retrieved Successfully',
            'count' => $count
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $User = Pembeli::find($id);

        if (is_null($User)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        if ($User->delete()) {
            return response([
                'message' => 'User Deleted Successfully',
                'data' => $User,
            ], 200);
        }

        return response([
            'message' => 'Delete User Failed',
            'data' => null,
        ], 400);
    }
}
