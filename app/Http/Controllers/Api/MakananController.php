<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MakananResource;
use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MakananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MakananResource::collection(Makanan::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_makanan' => 'required|string|max:255',
            'deskripsi_makanan' => 'required|string',
            'harga_makanan' => 'required|numeric',
            'stok_makanan' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('uploads/makanan', 'public');
        }

        $makanan = Makanan::create($data);

        return new MakananResource($makanan);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $makanan = Makanan::findOrFail($id);

        $request->validate([
            'nama_makanan' => 'sometimes|required|string|max:255',
            'deskripsi_makanan' => 'sometimes|required|string',
            'harga_makanan' => 'sometimes|required|numeric',
            'stok_makanan' => 'sometimes|required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($makanan->gambar) {
                Storage::disk('public')->delete($makanan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('uploads/makanan', 'public');
        }

        $makanan->update($data);

        return new MakananResource($makanan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $makanan = Makanan::findOrFail($id);

        if ($makanan->gambar) {
            Storage::disk('public')->delete($makanan->gambar);
        }

        $makanan->delete();

        return response()->json(['message' => 'Makanan berhasil dihapus']);
    }
    
}
