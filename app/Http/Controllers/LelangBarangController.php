<?php

namespace App\Http\Controllers;

use App\Models\LelangBarang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LelangBarangController extends Controller
{
    /**
     * Lihat semua barang lelang (misal untuk daftar).
     */
    public function index()
    {
        $items = LelangBarang::orderBy('waktu_mulai', 'desc')->get();
        return response()->json($items);
    }

    /**
     * Lihat detail satu barang lelang.
     */
    public function show($id)
    {
        $item = LelangBarang::findOrFail($id);
        return response()->json($item);
    }

    /**
     * Tambah barang lelang baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_awal' => 'required|integer|min:0',
            'waktu_mulai'   => ['required', 'date_format:Y-m-d H:i:s'],
            'waktu_selesai' => ['required', 'date_format:Y-m-d H:i:s', 'after:waktu_mulai'],
            'bid_time' => 'nullable',
        ]);

        $data['status'] = 'aktif';


        $lelang = LelangBarang::create($data);

        return response()->json($lelang, 201);
    }

    /**
 * Update data lelang (nama, deskripsi, harga_awal, waktu, status, dll).
 */
public function update(Request $request, $id)
{
    $lelang = LelangBarang::findOrFail($id);

    $rules = [
        'nama'           => 'sometimes|required|string|max:255',
        'deskripsi'      => 'sometimes|nullable|string',
        'harga_awal'     => 'sometimes|required|integer|min:0',
        'waktu_mulai'    => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
        'waktu_selesai'  => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
        'status'         => ['sometimes', Rule::in(['aktif', 'selesai', 'dibatalkan'])],
        'bid_time'       => 'sometimes|nullable|date_format:Y-m-d H:i:s',
    ];

    $data = $request->validate($rules);

    // // jika kedua waktu ada, pastikan selesai > mulai
    // if (isset($data['waktu_mulai'], $data['waktu_selesai'])) {
    //     if (strtotime($data['waktu_selesai']) <= strtotime($data['waktu_mulai'])) {
    //         return response()->json([
    //             'message' => 'waktu_selesai harus setelah waktu_mulai.',
    //         ], 422);
    //     }
    // }

    $lelang->fill($data);
    $lelang->save();

    return response()->json($lelang);
}




    /**
     * Tutup lelang secara manual (set status selesai).
     */
    public function tutupLelang($id)
    {
        $lelang = LelangBarang::findOrFail($id);

        if ($lelang->status === 'selesai') {
            return response()->json(['message' => 'Lelang sudah selesai.'], 400);
        }

        $lelang->status = 'selesai';
        $lelang->save();

        return response()->json($lelang);
    }

}
