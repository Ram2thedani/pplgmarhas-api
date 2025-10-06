<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Buku::query();

        // Check if there's a search query
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%");
            });
        }

        $buku = $query->paginate(10);

        $bukuCollection = $buku->getCollection()->map(function ($data) {
            return [
                'id' => $data->id,
                'judul' => $data->judul,
                'jml_halaman' => $data->jml_halaman,
                'pengarang' => $data->pengarang,
                'gambar' => $data->gambar,

            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $bukuCollection,
            'meta' => [
                'current_page' => $buku->currentPage(),
                'last_page' => $buku->lastPage(),
                'per_page' => $buku->perPage(),
                'total' => $buku->total(),
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
                'judul' => 'required|string|max:255',
                'pengarang' => 'required|string|max:255',
                'jml_halaman' => 'required|numeric',
                'gambar' => 'required',

                // Add other fields and rules here
            ],
            [
                'judul.required' => 'judul harus diisi',
                'pengarang.required' => 'pengarang harus diisi',
                'jml_halaman.required' => 'jumlah halaman harus diisi',
                'gambar.required' => 'gambar harus diisi',
            ]

        );

        $buku = Buku::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $buku
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $buku = buku::findOrFail($id);
            return response()->json($buku);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data buku tidak ditemukan'
            ], 404, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = buku::findOrFail($id);

        $validated = $request->validate(
            [
                'judul' => 'sometimes|string|max:255',
                'pengarang' => 'sometimes|string|max:255',
                'jml_halaman' => 'sometimes|numeric',
                'gambar' => 'sometimes',

                // Add other fields and rules here
            ]
        );

        $buku->update($validated);

        return response()->json([
            'status' => 'updated',
            'data' => $buku
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return response()->json(['status' => 'deleted']);
    }
}
