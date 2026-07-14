<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Complaint Saya';
        $complaints = Complaint::where('user_id', auth()->id())->with('category')->get();
        return view('user.complaint.index', compact('complaints', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Complaint';
        $categories = Category::all();
        return view('user.complaint.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $foto = $request->file('photo_path');

            unset($validated['photo_path']);

            $validated['user_id'] = auth()->id();
            $validated['status'] = 'baru';
            if ($foto) {
                $path = $foto->store('complaint', 'public');
                $validated['photo_path'] = $path;
            }
            Complaint::create($validated);

            DB::commit();
            return redirect()->route('user.complaint.index')->with(['message' => 'Pengaduan berhasil ditambahkan.', 'type-alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'message' => 'Terjadi kesalahan saat menyimpan Complaint: ' . $e->getMessage(),
                'type-alert' => 'danger'
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        $title = 'Detail Complaint';
        return view('user.complaint.show', compact('complaint', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $title = 'Edit Complaint';
        $categories = Category::all();
        return view('user.complaint.edit', compact('complaint', 'title', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $foto = $request->file('photo_path');

            unset($validated['photo_path']);
            if ($foto) {
                Storage::disk('public')->delete($complaint->photo_path);
                $path = $foto->store('complaint', 'public');
                $validated['photo_path'] = $path;
            }
            $complaint->update($validated);

            DB::commit();
            return redirect()->route('user.complaint.index')->with(['message' => 'Complaint berhasil diperbarui.', 'type-alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'message' => 'Terjadi kesalahan saat memperbarui Complaint: ' . $e->getMessage(),
                'type-alert' => 'danger'
            ])->withInput();
        }
    }

    public function modal_delete($id)
    {
        $complaint = Complaint::findorfail($id);
        $title = "Hapus Complain: " . $complaint->title;
        $view = view('user.complaint.delete', compact('title', 'complaint'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        try {
            DB::beginTransaction();
            Storage::disk('public')->delete($complaint->photo_path);
            $complaint->delete();
            DB::commit();

            return redirect()->route('user.complaint.index')->with(['message' => 'Complaint berhasil dihapus.', 'type-alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('user.complaint.index')->withErrors([
                'message' => 'Terjadi kesalahan saat menghapus complaint: ' . $e->getMessage(),
                'type-alert' => 'danger'
            ]);
        }
    }
}
