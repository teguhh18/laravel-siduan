<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TanggapiPengaduanRequest;
use App\Http\Resources\PengaduanResource;
use App\Models\Pengaduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    /**
     * Display a listing of all pengaduan (admin view).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Pengaduan::with(['kategoriPengaduan', 'user'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $pengaduan = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => PengaduanResource::collection($pengaduan),
            'meta' => [
                'current_page' => $pengaduan->currentPage(),
                'last_page' => $pengaduan->lastPage(),
                'per_page' => $pengaduan->perPage(),
                'total' => $pengaduan->total(),
            ],
        ]);
    }

    /**
     * Display the specified pengaduan detail (admin view).
     */
    public function show(Pengaduan $pengaduan): JsonResponse
    {
        $pengaduan->load(['kategoriPengaduan', 'user']);

        return response()->json([
            'success' => true,
            'data' => new PengaduanResource($pengaduan),
        ]);
    }

    /**
     * Update pengaduan status and tanggapan (admin response).
     */
    public function update(TanggapiPengaduanRequest $request, Pengaduan $pengaduan): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pengaduan->update([
                'status' => $request->status,
                'pesan_tanggapan' => $request->pesan_tanggapan,
            ]);
            DB::commit();

            $pengaduan->load(['kategoriPengaduan', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil ditanggapi.',
                'data' => new PengaduanResource($pengaduan),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menanggapi pengaduan.',
            ], 500);
        }
    }
}
