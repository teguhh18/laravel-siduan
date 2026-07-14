<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kategori Pengaduan';
        $categories = Category::all();
        return view('admin.category.index', compact('categories', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Kategori';
        return view('admin.category.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required',
            'is_active' => 'nullable|boolean'
        ]);
        DB::beginTransaction();
        try {
            Category::create($validated);
            DB::commit();
            return redirect()->route('admin.category.index')->with(['message' => 'Kategori berhasil ditambahkan.'], ['type-alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['message' => 'Gagal menambahkan Kategori: ' . $e->getMessage()], ['type-alert' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $title = "Hapus Kategori: " . $category->name;
        $view = view('admin.category.delete', compact('title', 'category'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $title = 'Edit Kategori';
        return view('admin.category.edit', compact('category', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required',
            'is_active' => 'nullable|boolean'
        ]);
        DB::beginTransaction();
        try {
            $category->update($validated);
            DB::commit();
            return redirect()->route('admin.category.index')->with(['message' => 'Kategori berhasil diubah.'], ['type-alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['message' => 'Gagal mengubah Kategori.'], ['type-alert' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $category->delete();
            DB::commit();

            return redirect()->route('admin.category.index')->with([
                'message' => 'Kategori berhasil dihapus.',
                'type-alert' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.category.index')->with([
                'message' => 'Gagal menghapus Kategori.',
                'type-alert' => 'error',
            ]);
        }
    }
}
