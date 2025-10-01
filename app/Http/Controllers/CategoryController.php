<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        
        if ($request->filled('nama_kategori')) {
            $query->where('nama_kategori', 'like', '%' . $request->nama_kategori . '%');
        }

        if ($request->filled('deskripsi')) {
            $query->where('deskripsi', 'like', '%' . $request->deskripsi . '%');
        }

        $categories = $query->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori',
            'data'    => $categories->items(),
            'meta'    => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'per_page'     => $categories->perPage(),
                'total'        => $categories->total(),
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
            'deskripsi' => 'string',
        ]);

        $category = Category::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data'    => $category
        ], 201);
    }

    // Menampilkan detail kategori tertentu
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail kategori',
            'data'    => $category
        ], 200);
    }

    // Update kategori
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama_kategori' => 'sometimes|required|string|max:255' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori ?? $category->nama_kategori,
            'deskripsi' => $request->deskripsi ?? $category->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $category
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }
}
