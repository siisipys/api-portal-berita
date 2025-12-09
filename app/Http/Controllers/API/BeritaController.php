<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of all berita.
     */
    public function index()
    {
        $berita = Berita::with(['user:id,name', 'komentar'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $berita
        ], 200);
    }

    /**
     * Store a newly created berita.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,published',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'user_id' => $request->user()->id,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori ?? 'umum',
            'status' => $request->status ?? 'published',
        ];

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/berita', $imageName);
            $data['gambar'] = 'berita/' . $imageName;
        }

        $berita = Berita::create($data);
        $berita->load('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Berita created successfully',
            'data' => $berita
        ], 201);
    }

    /**
     * Display the specified berita.
     */
    public function show($id)
    {
        $berita = Berita::with(['user:id,name', 'komentar.user:id,name'])->find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $berita
        ], 200);
    }

    /**
     * Update the specified berita.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita not found'
            ], 404);
        }

        // Check if user owns the berita
        if ($berita->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'sometimes|required|string|max:255',
            'konten' => 'sometimes|required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,published',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('judul')) $berita->judul = $request->judul;
        if ($request->has('konten')) $berita->konten = $request->konten;
        if ($request->has('kategori')) $berita->kategori = $request->kategori;
        if ($request->has('status')) $berita->status = $request->status;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($berita->gambar) {
                Storage::delete('public/' . $berita->gambar);
            }
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/berita', $imageName);
            $berita->gambar = 'berita/' . $imageName;
        }

        $berita->save();
        $berita->load('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Berita updated successfully',
            'data' => $berita
        ], 200);
    }

    /**
     * Remove the specified berita.
     */
    public function destroy(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita not found'
            ], 404);
        }

        // Check if user owns the berita
        if ($berita->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete image if exists
        if ($berita->gambar) {
            Storage::delete('public/' . $berita->gambar);
        }

        $berita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita deleted successfully'
        ], 200);
    }

    /**
     * Get berita by category
     */
    public function byCategory($kategori)
    {
        $berita = Berita::with(['user:id,name'])
            ->where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $berita
        ], 200);
    }

    /**
     * Search berita
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $berita = Berita::with(['user:id,name'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                    ->orWhere('konten', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $berita
        ], 200);
    }
}

