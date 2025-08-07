<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::paginate(10); // Choose your page size

        $siswaCollection = $siswa->getCollection()->map(function ($data) {
            return [
                'id' => $data->id,
                'nama' => $data->nama,
                'kelas' => $data->kelas,
                'jenis_kelamin' => $data->jenis_kelamin,
                'alamat' => $data->alamat,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $siswaCollection,
            'meta' => [
                'current_page' => $siswa->currentPage(),
                'last_page' => $siswa->lastPage(),
                'per_page' => $siswa->perPage(),
                'total' => $siswa->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nama' => 'required|string|max:255',
                'kelas' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',

                // Add other fields and rules here
            ],
            [
                'nama.required' => 'Nama harus diisi',
                'kelas.required' => 'Kelas harus diisi',
                'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
                'alamat.required' => 'Alamat kelamin harus diisi',
            ]

        );

        $siswa = Siswa::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $siswa
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $siswa = Siswa::findOrFail($id); // Throws 404 if not found
        return response()->json($siswa); // Sends the result as JSON
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->only('nama_lengkap'));

        return response()->json([
            'status' => 'updated',
            'data' => $siswa
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return response()->json(['status' => 'deleted']);
    }
}
