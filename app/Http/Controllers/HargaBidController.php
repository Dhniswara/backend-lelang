<?php

namespace App\Http\Controllers;

use App\Models\HargaBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HargaBidController extends Controller
{
    public function index() {
        $allData = HargaBid::all();

        return response()->json($allData);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'harga'       => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $hargaBid = HargaBid::create([
            'harga'       => $request->harga,
            'user_id'     => $user->id

        ]);

        return response()->json([
            'message' => 'harga berhasil ditambahkan.',
            'data'    => $hargaBid
        ], 201);
    }
    
}
