<?php

namespace App\Http\Controllers;

use App\Models\gallery;
use App\Models\Pegawai;
use App\Models\Penitipan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = gallery::with('Penitipan_Barang');
        if ($request->has('search') && $request->search != '') {
            $query->where('id_gallery', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $gallery = $query->paginate($perPage);


        return response([
            'message' => 'All gallery Retrieved',
            'data' => $gallery
        ], 200);
    }
    public function getData()
    {
        $data = gallery::all();

        return response([
            'message' => 'All JenisKamar Retrieved',
            'data' => $data
        ], 200);
    }
    public function getDataByBarangId($id)
    {
        $data = gallery::with('Penitipan_Barang')->where('id_barang', $id)->get();

        if ($data->isNotEmpty()) {
            return response([
                'message' => 'Data Retrieved Successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'message' => 'Gallery Not Found',
                'data' => null
            ], 404);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            // 'title' => 'required',
            'foto' => 'required',
            'id_barang' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = gallery::latest('id_gallery')->first();
        $newId = $lastId ? 'GL-' . str_pad((int) substr($lastId->id_gallery, 3) + 1, 4, '0', STR_PAD_LEFT) : 'GL-0001';
        $storeData['id_gallery'] = $newId;

        $idUser = Auth::id();
        $user = Pegawai::find($idUser);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found'
            ], 404);
        }
        if ($user->id_jabatan == 'J-004') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        if ($request->hasFile('foto')) {
            $uploadFolder = 'FotoBarang';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            $storeData['foto'] = $uploadedImageResponse;
        }

        $gallery = gallery::create($storeData);
        return response([
            'message' => 'gallery Added Successfully',
            'data' => $gallery,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show(string $id)
    {
        $gallery = gallery::find($id);

        if ($gallery) {
            return response([
                'message' => 'gallery Found',
                'data' => $gallery
            ], 200);
        }

        return response([
            'message' => 'gallery Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gallery = gallery::find($id);
        if (is_null($gallery)) {
            return response([
                'message' => 'gallery Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'title' => 'required',
            'id_barang' => 'required',
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
        if ($user->id_jabatan == 'J-004') {
            return response([
                'message' => 'User Cannot'
            ], 404);
        }
        if ($request->hasFile('foto')) {
            $uploadFolder = 'FotoBarang';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            $updateData['foto'] = $uploadedImageResponse;
        }
        $gallery->update($updateData);
        return response([
            'message' => 'gallery Updated Successfully',
            'data' => $gallery,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = gallery::find($id);

        if (is_null($gallery)) {
            return response([
                'message' => 'gallery Not Found',
                'data' => null
            ], 404);
        }
        if ($gallery->delete()) {
            return response([
                'message' => 'gallery Deleted Successfully',
                'data' => $gallery,
            ], 200);
        }

        return response([
            'message' => 'Delete gallery Failed',
            'data' => null,
        ], 400);
    }
}
