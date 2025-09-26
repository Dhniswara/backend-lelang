<?php

namespace App\Http\Controllers;

use App\Models\HargaBid;
use App\Models\LelangBarang;
use App\Models\Nipl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HargaBidController extends Controller
{
    public function index() {
        $allData = HargaBid::with(['user', 'lelang'])->get();

        return response()->json($allData); 
    }

    public function store(Request $request) {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'harga'     => 'required|integer|min:1',
            'lelang_id' => 'required|exists:lelang_barangs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // User Belum punya NIPL
        $nipl = Nipl::where('user_id', $user->id)->first();

        if (!$nipl) {
            return response()->json([
                "message" => "anda belum memiliki NIPL, silahkan membuat NIPL terlebih dahulu   "
            ], 403);
        }

        // Lelang selesai
        $notActive = LelangBarang::where('status', 'selesai')
        ->where('id', $request->lelang_id)
        ->exists();

        if ($notActive) {
            return response()->json([
                "message" => "Lelang telah ditutup"
            ], 403);
        }

        $lelang = LelangBarang::find($request->lelang_id);

        // Logic jika harga yang ditawarkan user dibawah harga awal
        if ($request->harga <= $lelang->harga_awal) {
            return response()->json([
                'message' => 'Harga yang dipasang harus lebih tinggi dari harga awal'
            ], 403);
        }

        // Cek harga tertinggi saat ini
        $hargaTertinggi = HargaBid::where('lelang_id', $request->lelang_id)->max('harga');

        if ($hargaTertinggi && $request->harga <= $hargaTertinggi) {
            return response()->json([
                'message' => 'Tawaran harus lebih tinggi dari harga tertinggi saat ini.',
                'harga tertinggi saat ini' => $hargaTertinggi
            ], 403);
        }


        $hargaBid = HargaBid::create([
            'harga'     => $request->harga,
            'user_id'   => $user->id,
            'lelang_id' => $request->lelang_id
        ]);

        $hargaTertinggiNow = HargaBid::where('lelang_id', $request->lelang_id)->max('harga');

        // Update harga_akhir di tabel lelang
        $lelang->update([
            'harga_akhir' => $hargaTertinggiNow
        ]);

        return response()->json([
            'message' => 'Tawaran berhasil diajukan.',
            'data'    => $hargaBid
        ], 201);
    }
}
