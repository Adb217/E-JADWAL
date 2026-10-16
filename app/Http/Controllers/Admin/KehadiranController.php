<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\KehadiranGuru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KehadiranController extends Controller
{
    public function index(Request $r)
    {
        $guru = Guru::with('kehadiranHariIni')->where('status', '!=', 'Nonaktif')
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('nama', 'ilike', "%$s%")
                ->orWhere('kode', 'ilike', "%$s%")))
            ->orderBy('nama')->get();

        return view('admin.kehadiran.index', compact('guru'));
    }

    public function update(Request $r, Guru $guru)
    {
        $d = $r->validate(['status' => ['required', Rule::in(KehadiranGuru::STATUS)]]);

        KehadiranGuru::updateOrCreate(
            ['guru_id' => $guru->id, 'tanggal' => today()->toDateString()],
            ['status' => $d['status'], 'sumber' => 'admin'],
        );

        return back();
    }
}