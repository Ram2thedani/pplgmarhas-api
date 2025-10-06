<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Check if there's a search query
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('jenis_kelamin', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
$query->orderBy('created_at', 'desc');
        // Paginate filtered results
        $siswa = $query->paginate(10);

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
                'alamat.required' => 'Alamat harus diisi',
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
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json($siswa);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan'
            ], 404, [], JSON_PRETTY_PRINT);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'kelas' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string|max:255',
            'jenis_kelamin' => 'sometimes|string|max:255',
        ]);

        $siswa->update($validated);

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
