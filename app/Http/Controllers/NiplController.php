<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nipl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NiplController extends Controller
{

    public function index(Request $request) {
        $user = $request->user();
        $nipl = Nipl::where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Daftar Nipl user.', 
            'data' => $nipl
        ], 200);
    }

    public function show(Request $request, $id) {
        $user = $request->user();
        $nipl = Nipl::where('id', $id)->where('user_id', $user->id)->first();

        if (! $nipl) {
            return response()->json([
                'message' => 'Nipl tidak ditemukan atau bukan milik Anda.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail Nipl.', 
            'data' => $nipl
        ]);
    }

    // public function store(Request $request) {
    //     $user = $request->user();

    //     if (Nipl::where('user_id', $user->id)->exists()) {
    //     return response()->json([
    //         'message' => 'User sudah memiliki NIPL.',
    //     ], 409);
    // }

    //     $validator = Validator::make($request->all(), [
    //         'email'       => 'required|email',
    //         'no_telepon'  => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validasi gagal.',
    //             'errors'  => $validator->errors(),
    //         ], 422);
    //     }

    //     $noNipl = str_pad(mt_rand(0, 999999), 8, '0', STR_PAD_LEFT);

    //     $nipl = Nipl::create([
    //         'user_id'     => $user->id,
    //         'no_nipl'     => $noNipl,
    //         'email'       => Auth::user()->email,
    //         'no_telepon'  => $request->no_telepon,
    //     ]);

    //     return response()->json([
    //         'message' => 'Nipl berhasil dibuat.',
    //         'data'    => $nipl
    //     ], 201);
    // }

    public function destroy(Request $request, $id) {
        $user = $request->user();
        $nipl = Nipl::where('id', $id)->where('user_id', $user->id)->first();

        if (! $nipl) {
            return response()->json([
                'message' => 'Nipl tidak ditemukan atau bukan milik Anda.'
            ], 404);
        }

        $nipl->delete();

        return response()->json([
            'message' => 'Nipl berhasil dihapus.'
        ]);
    }
}
