<?php

namespace App\Http\Controllers;

use App\Models\ResetLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResetLevelController extends Controller
{
    public function updateReset(Request $request)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
            'lama_hari' => 'required|integer|min:1|max:365',
            'jam_reset' => 'required'
        ]);

        $reset = ResetLevel::updateOrCreate(
            ['id' => 1],
            [
                'status' => $request->status,
                'lama_hari' => $request->lama_hari,
                'jam_reset' => $request->jam_reset,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan reset berhasil disimpan',
            'data' => [
                'status' => $reset->status,
                'lama_hari' => $reset->lama_hari,
                'jam_reset' => Carbon::parse($reset->jam_reset)->format('H:i'),
                'last_reset_at' => $reset->last_reset_at,
            ]
        ]);
    }

    public function statusReset()
    {
        $reset = ResetLevel::first();

        return response()->json([
            'data' => [
                'status' => $reset->status,
                'lama_hari' => $reset->lama_hari,
                'jam_reset' => Carbon::parse($reset->jam_reset)->format('H:i'),
                'last_reset_at' => $reset->last_reset_at,
            ]
        ]);
    }
}
