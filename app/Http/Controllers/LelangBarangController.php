<?php

namespace App\Http\Controllers;

use App\Models\LelangBarang;
use Illuminate\Http\Request;

class LelangBarangController extends Controller
{

    public function index()
    {
        $items = LelangBarang::with('category')->get();
        return response()->json($items);
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
        'harga_awal'    => 'required|integer|min:0',
        'waktu_mulai'   => 'required|date_format:Y-m-d H:i:s',
        'waktu_selesai' => 'required|date_format:Y-m-d H:i:s|after:waktu_mulai',
        'bid_time'      => 'nullable'
    ]);

    if ($request->hasFile('gambar_barang')) {
        $gambarBarang = $request->file('gambar_barang');
        $namaGambar = uniqid() . '.' . $gambarBarang->getClientOriginalExtension();

        // Simpan file ke storage/app/public/gambar-barang
        $gambarBarang->storeAs('public/gambar-barang', $namaGambar);

        // Simpan path publik ke database
        $data['gambar_barang'] = 'storage/gambar-barang/' . $namaGambar;
    }

    $data['status'] = 'aktif';

    $lelang = LelangBarang::create($data);

    // Tambahkan full URL biar di frontend gampang dipakai
    $lelang->gambar_barang_url = url($lelang->gambar_barang);

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
            'harga_awal'    => 'sometimes|required|integer',
            'waktu_mulai'   => 'sometimes|required|date_format:Y-m-d H:i:s',
            'waktu_selesai' => 'sometimes|required|date_format:Y-m-d H:i:s|after:waktu_mulai',
            'status'        => 'sometimes',
            'bid_time'      => 'sometimes|nullable|date_format:Y-m-d H:i:s',
        ]);

        if ($request->hasFile('gambar_barang')) {
            if ($barang->gambar_barang && file_exists(public_path($barang->gambar_barang))) {
                unlink(public_path($barang->gambar_barang));
            }

            $gambarBarang = $request->file('gambar_barang');
            $namaGambar = uniqid() . '.' . $gambarBarang->getClientOriginalExtension();
            $gambarBarang->move(public_path('gambar-barang'), $namaGambar);

            $data['gambar_barang'] = 'gambar-barang/' . $namaGambar;
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
