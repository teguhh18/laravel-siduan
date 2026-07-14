<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Complaint Masyarakat';
        $complaints = Complaint::with(['category', 'user'])->get();
        return view('admin.complaint.index', compact('complaints', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $title = "Ubah Data : " . $complaint->title;
        $view = view('admin.complaint.edit', compact('title', 'complaint'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'note' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $complaint->update([
                'status' => $request->status,
            ]);

            // Buat RIwayat
            ComplaintStatusLog::create([
                'complaint_id' => $complaint->id,
                'changed_by' => auth()->user()->id,
                'status' =>  $complaint->status,
                'note' =>  $request->note,
            ]);

            DB::commit();
            return redirect()->route('admin.complaint.index')->with(['message' => 'Complaint berhasil Diubah.'], ['type-alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['message' => 'Gagal menambahkan Kategori: ' . $e->getMessage()], ['type-alert' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
}
