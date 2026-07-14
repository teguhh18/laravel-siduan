<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePengaduanRequest;
use App\Http\Requests\Api\UpdatePengaduanRequest;
use App\Http\Resources\PengaduanResource;
use App\Models\Pengaduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the user's own pengaduan.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Pengaduan::where('user_id', $request->user()->id)
            ->with('kategoriPengaduan')
            ->latest();

        // Optional filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
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
     * Store a newly created pengaduan.
     */
    public function store(StorePengaduanRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            unset($data['foto']);

            $data['user_id'] = $request->user()->id;
            $data['status'] = 'pending';

            $pengaduan = Pengaduan::create($data);

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('pengaduan', 'public');
                $pengaduan->update(['foto' => $path]);
            }

            DB::commit();

            $pengaduan->load('kategoriPengaduan');

            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil ditambahkan.',
                'data' => new PengaduanResource($pengaduan),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pengaduan.',
            ], 500);
        }
    }

    /**
     * Display the specified pengaduan (own only).
     */
    public function show(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        // Ownership check
        if ($pengaduan->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pengaduan ini.',
            ], 403);
        }

        $pengaduan->load(['kategoriPengaduan', 'user']);

        return response()->json([
            'success' => true,
            'data' => new PengaduanResource($pengaduan),
        ]);
    }

    /**
     * Update the specified pengaduan (own only, only if still pending).
     */
    public function update(UpdatePengaduanRequest $request, Pengaduan $pengaduan): JsonResponse
    {
        // Ownership check
        if ($pengaduan->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pengaduan ini.',
            ], 403);
        }

        // Only allow edit if status is still pending
        if ($pengaduan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan yang sudah diproses tidak dapat diedit.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->validated();
            unset($data['foto']);

            $pengaduan->update($data);

            if ($request->hasFile('foto')) {
                // Delete old photo
                if ($pengaduan->foto) {
                    Storage::disk('public')->delete($pengaduan->foto);
                }
                $path = $request->file('foto')->store('pengaduan', 'public');
                $pengaduan->update(['foto' => $path]);
            }

            DB::commit();

            $pengaduan->load('kategoriPengaduan');

            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil diperbarui.',
                'data' => new PengaduanResource($pengaduan),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pengaduan.',
            ], 500);
        }
    }

    /**
     * Remove the specified pengaduan (own only, only if still pending).
     */
    public function destroy(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        // Ownership check
        if ($pengaduan->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pengaduan ini.',
            ], 403);
        }

        // Only allow delete if status is still pending
        if ($pengaduan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan yang sudah diproses tidak dapat dihapus.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            $pengaduan->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengaduan.',
            ], 500);
        }
    }
}
