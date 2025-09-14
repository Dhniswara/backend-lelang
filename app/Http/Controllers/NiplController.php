<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nipl;

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
