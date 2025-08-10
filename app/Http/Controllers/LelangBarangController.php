<?php

namespace App\Http\Controllers;

use App\Models\LelangBarang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LelangBarangController extends Controller
{

    public function index()
    {
        $items = LelangBarang::orderBy('waktu_mulai', 'desc')->get();
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
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_awal' => 'required|integer|min:0',
            'waktu_mulai'   => ['required', 'date_format:Y-m-d H:i:s'],
            'waktu_selesai' => ['required', 'date_format:Y-m-d H:i:s', 'after:waktu_mulai'],
            'bid_time' => 'nullable'

        ]);

        $data['status'] = 'aktif';


        $lelang = LelangBarang::create($data);

        return response()->json($lelang, 201);
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'nama_barang' => 'sometimes|required|string|max:255',
        'deskripsi' => 'sometimes|nullable|string',
        'harga_awal' => 'sometimes|required|integer',
        'waktu_mulai' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
        'waktu_selesai' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
        'status' => ['sometimes', Rule::in(['aktif', 'selesai', 'dibatalkan'])],
        'bid_time' => 'sometimes|nullable|date_format:Y-m-d H:i:s',
    ]);

    // $data = $request->validate($rules); // Mengambil data yang lolos validasi

    $lelang = LelangBarang::findOrFail($id);

    // $lelang->fill($data); 
    $lelang->nama_barang = $request->nama_barang;
    $lelang->deskripsi = $request->deskripsi;
    $lelang->harga_awal = $request->harga_awal;
    $lelang->waktu_mulai = $request->waktu_mulai;
    $lelang->waktu_selesai = $request->waktu_selesai;
    $lelang->status = $request->status;
    $lelang->bid_time = $request->bid_time;
    $lelang->save();

    return response()->json([
        "message" => "barang berhasil di update"
    ], 200);
}


    // Hapus barang
    public function destroy( $id )
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
