<?php

namespace App\Http\Controllers;

use App\Models\LelangBarang;
use Illuminate\Http\Request;

class LelangBarangController extends Controller
{

    public function index()
    {
        $items = LelangBarang::all();
        return response()->json($items);
    }

    public function show($id)
    {
        $item = LelangBarang::findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gambar_barang' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_awal' => 'required|integer|min:0',
            'waktu_mulai'   => 'required|date_format:Y-m-d H:i:s',
            'waktu_selesai' => 'required|date_format:Y-m-d H:i:s|after:waktu_mulai',
            'bid_time' => 'nullable'
        ]);

        if ($request->hasFile('gambar_barang')) {
            $gambarBarang = $request->file('gambar_barang');
            $namaGambar = uniqid() . '.' . $gambarBarang->getClientOriginalExtension();
            $gambarBarang->move(public_path('gambar-barang'), $namaGambar);

            $data['gambar_barang'] = 'gambar-barang/' . $namaGambar;
        }

        $data['status'] = 'aktif';

        $lelang = LelangBarang::create($data);

        return response()->json($lelang, 201);
    }
    
    
    public function update(Request $request, $id)
    {
        $barang = LelangBarang::findOrFail($id);

        $request->validate([
            'gambar_barang' => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048',
            'nama_barang'   => 'sometimes|required|string|max:255',
            'deskripsi'     => 'sometimes|nullable|string',
            'harga_awal'    => 'sometimes|required|integer',
            'waktu_mulai'   => 'sometimes|required|date_format:Y-m-d H:i:s',
            'waktu_selesai' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'status'        => 'sometimes',
            'bid_time'      => 'sometimes|nullable|date_format:Y-m-d H:i:s',
        ]);

        if ($request->hasFile('gambar_barang')) {
            // Hapus avatar lama kalau ada
            if ($barang->avatar && file_exists(public_path($barang->avatar))) {
                unlink(public_path($barang->avatar));
            }

            $gambarBarang = $request->file('gambar_barang');
            $namaGambar = uniqid() . '.' . $gambarBarang->getClientOriginalExtension();
            $gambarBarang->move(public_path('gambar-barang'), $namaGambar);

            $data['gambar_barang'] = 'gambar-barang/' . $namaGambar;
        }

        $barang->nama_barang = $request->nama_barang;
        $barang->deskripsi = $request->deskripsi;
        $barang->harga_awal = $request->harga_awal;
        $barang->waktu_mulai = $request->waktu_mulai;
        $barang->waktu_selesai = $request->waktu_selesai;
        // $barang->status = $request->status;
        // $barang->bid_time = $request->bid_time;
        $barang->save();

        return response()->json([
            "message" => "barang berhasil di update"
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
