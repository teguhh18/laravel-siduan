<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics based on user role.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    /**
     * Admin dashboard: overview of all pengaduan.
     */
    private function adminDashboard(): JsonResponse
    {
        $totalPengaduan = Pengaduan::count();
        $totalPending = Pengaduan::where('status', 'pending')->count();
        $totalProses = Pengaduan::where('status', 'proses')->count();
        $totalSelesai = Pengaduan::where('status', 'selesai')->count();

        $perKategori = KategoriPengaduan::withCount('pengaduan')
            ->orderByDesc('pengaduan_count')
            ->get()
            ->map(fn($k) => [
                'id' => $k->id,
                'nama_kategori' => $k->nama_kategori,
                'jumlah' => $k->pengaduan_count,
            ]);

        $recentPengaduan = Pengaduan::with(['kategoriPengaduan', 'user'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'judul' => $p->judul,
                'status' => $p->status,
                'user' => $p->user->name ?? '-',
                'kategori' => $p->kategoriPengaduan->nama_kategori ?? '-',
                'created_at' => $p->created_at,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'role' => 'admin',
                'total_pengaduan' => $totalPengaduan,
                'total_pending' => $totalPending,
                'total_proses' => $totalProses,
                'total_selesai' => $totalSelesai,
                'per_kategori' => $perKategori,
                'recent_pengaduan' => $recentPengaduan,
            ],
        ]);
    }

    /**
     * User dashboard: overview of own pengaduan.
     */
    private function userDashboard($user): JsonResponse
    {
        $myPengaduan = Pengaduan::where('user_id', $user->id);

        $totalPengaduan = (clone $myPengaduan)->count();
        $totalPending = (clone $myPengaduan)->where('status', 'pending')->count();
        $totalProses = (clone $myPengaduan)->where('status', 'proses')->count();
        $totalSelesai = (clone $myPengaduan)->where('status', 'selesai')->count();

        $recentPengaduan = Pengaduan::where('user_id', $user->id)
            ->with('kategoriPengaduan')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'judul' => $p->judul,
                'status' => $p->status,
                'kategori' => $p->kategoriPengaduan->nama_kategori ?? '-',
                'created_at' => $p->created_at,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'role' => 'user',
                'total_pengaduan' => $totalPengaduan,
                'total_pending' => $totalPending,
                'total_proses' => $totalProses,
                'total_selesai' => $totalSelesai,
                'recent_pengaduan' => $recentPengaduan,
            ],
        ]);
    }
}
