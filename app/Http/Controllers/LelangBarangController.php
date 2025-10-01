<?php

namespace App\Http\Controllers;

use App\Models\LelangBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LelangBarangController extends Controller
{

    public function index(Request $request)
    {
        $query = LelangBarang::with(['category']);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_harga')) {
            $query->where('harga_awal', '>=', $request->min_harga);
        }
        if ($request->filled('max_harga')) {
            $query->where('harga_awal', '<=', $request->max_harga);
        }

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        // Jika ?all=true maka ambil semua data tanpa pagination
        if ($request->boolean('all')) {
            $items = $query->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada barang dengan filter yang diinginkan',
                    'data'    => [],
                    'meta'    => null,
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar barang lelang',
                'data'    => $items,
                'meta'    => null,
            ], 200);
        }

        // Default: paginated
        $items = $query->paginate(10);

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada barang dengan ketentuan filter yang diinginkan',
                'data'    => [],
                'meta'    => [
                    'current_page' => $items->currentPage(),
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $items->perPage(),
                    'total'        => $items->total(),
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar barang lelang',
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ]
        ], 200);
    }


    public function show($id)
    {
        $item = LelangBarang::with('category')->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gambar_barang' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'nama_barang'   => 'required|string|max:255',
            'kategori_id'   => 'required|exists:categories,id',
            'deskripsi'     => 'required|string',
            'harga_awal'    => 'required|min:0|numeric',
            'waktu_mulai'   => 'required|date_format:Y-m-d H:i:s',
            'waktu_selesai' => 'required|date_format:Y-m-d H:i:s|after:waktu_mulai',
            'bid_time'      => 'nullable'
        ]);

        if ($request->file('gambar_barang')) {
            $data['gambar_barang'] = $request->file('gambar_barang')->store('gambar-barang');
        }

        $data['status'] = 'aktif';

        $lelang = LelangBarang::create($data);

        return response()->json($lelang, 201);
    }


    public function update(Request $request, $id)
    {
        $barang = LelangBarang::findOrFail($id);

        $data = $request->validate([
            'gambar_barang' => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048',
            'nama_barang'   => 'sometimes|required|string|max:255',
            'kategori_id'   => 'sometimes|exists:categories,id',
            'deskripsi'     => 'sometimes|nullable|string',
            'harga_awal'    => 'sometimes|numeric',
            'waktu_mulai'   => 'sometimes|date_format:Y-m-d H:i:s',
            'waktu_selesai' => 'sometimes|date_format:Y-m-d H:i:s|after:waktu_mulai',
            'status'        => 'sometimes',
        ]);

        if ($request->file('gambar_barang')) {
            if ($barang->gambar_barang && Storage::exists($barang->gambar_barang)) {
                Storage::delete($barang->gambar_barang);
            }
            $data['gambar_barang'] = $request->file('gambar_barang')->store('gambar-barang');
        }


        $barang->update($data);

        return response()->json([
            "message" => "Barang berhasil diupdate",
            "data" => $barang
        ], 200);
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = LelangBarang::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan.'], 404);
        }

        $barang->delete();

        return response()->json([
            'message' => 'Barang berhasil dihapus'
        ], 200);
    }
}
