<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriPengaduanResource;
use App\Models\KategoriPengaduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriPengaduanController extends Controller
{
    /**
     * Display a listing of kategori pengaduan.
     * Accessible by all authenticated users (for dropdown forms).
     */
    public function index(): JsonResponse
    {
        $kategori = KategoriPengaduan::withCount('pengaduan')
            ->orderBy('nama_kategori')
            ->get();

        return response()->json([
            'success' => true,
            'data' => KategoriPengaduanResource::collection($kategori),
        ]);
    }

    /**
     * Store a newly created kategori pengaduan.
     * Admin only.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $kategori = KategoriPengaduan::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori pengaduan berhasil ditambahkan.',
                'data' => new KategoriPengaduanResource($kategori),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori pengaduan.',
            ], 500);
        }
    }

    /**
     * Display the specified kategori pengaduan.
     * Admin only.
     */
    public function show(KategoriPengaduan $kategoriPengaduan): JsonResponse
    {
        $kategoriPengaduan->loadCount('pengaduan');

        return response()->json([
            'success' => true,
            'data' => new KategoriPengaduanResource($kategoriPengaduan),
        ]);
    }

    /**
     * Update the specified kategori pengaduan.
     * Admin only.
     */
    public function update(Request $request, KategoriPengaduan $kategoriPengaduan): JsonResponse
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $kategoriPengaduan->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori pengaduan berhasil diperbarui.',
                'data' => new KategoriPengaduanResource($kategoriPengaduan),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori pengaduan.',
            ], 500);
        }
    }

    /**
     * Remove the specified kategori pengaduan.
     * Admin only.
     */
    public function destroy(KategoriPengaduan $kategoriPengaduan): JsonResponse
    {
        DB::beginTransaction();
        try {
            $kategoriPengaduan->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori pengaduan berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori pengaduan.',
            ], 500);
        }
    }
}
