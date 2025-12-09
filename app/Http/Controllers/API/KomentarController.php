<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomentarController extends Controller
{
    /**
     * Display all komentar for a berita.
     */
    public function index($beritaId)
    {
        $berita = Berita::find($beritaId);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita not found'
            ], 404);
        }

        $komentar = Komentar::with('user:id,name')
            ->where('berita_id', $beritaId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $komentar
        ], 200);
    }

    /**
     * Store a newly created komentar.
     */
    public function store(Request $request, $beritaId)
    {
        $berita = Berita::find($beritaId);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'isi' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $komentar = Komentar::create([
            'user_id' => $request->user()->id,
            'berita_id' => $beritaId,
            'isi' => $request->isi,
        ]);

        $komentar->load('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Komentar added successfully',
            'data' => $komentar
        ], 201);
    }

    /**
     * Update the specified komentar.
     */
    public function update(Request $request, $beritaId, $id)
    {
        $komentar = Komentar::where('berita_id', $beritaId)->find($id);

        if (!$komentar) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar not found'
            ], 404);
        }

        // Check if user owns the komentar
        if ($komentar->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'isi' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $komentar->isi = $request->isi;
        $komentar->save();
        $komentar->load('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Komentar updated successfully',
            'data' => $komentar
        ], 200);
    }

    /**
     * Remove the specified komentar.
     */
    public function destroy(Request $request, $beritaId, $id)
    {
        $komentar = Komentar::where('berita_id', $beritaId)->find($id);

        if (!$komentar) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar not found'
            ], 404);
        }

        // Check if user owns the komentar
        if ($komentar->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $komentar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar deleted successfully'
        ], 200);
    }
}


