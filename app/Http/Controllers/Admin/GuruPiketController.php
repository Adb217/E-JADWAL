<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Guru, GuruPiket, Jadwal};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuruPiketController extends Controller
{
    public function index()
    {
        $piket = GuruPiket::with('guru')->get()
            ->sortBy(fn ($p) => array_search($p->hari, Jadwal::HARI))->values();

        return view('admin.guru-piket.index', [
            'piket' => $piket,
            'guru' => Guru::where('status', 'Aktif')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $r)
    {
        GuruPiket::create($this->valid($r));
        return back()->with('success', 'Guru piket berhasil ditambahkan.');
    }

    public function update(Request $r, GuruPiket $guruPiket)
    {
        $guruPiket->update($this->valid($r));
        return back()->with('success', 'Guru piket berhasil diperbarui.');
    }

    public function destroy(GuruPiket $guruPiket)
    {
        $guruPiket->delete();
        return back()->with('success', 'Guru piket dihapus.');
    }

    private function valid(Request $r): array
    {
        return $r->validate([
            'guru_id' => 'required|exists:gurus,id',
            'hari' => ['required', Rule::in(Jadwal::HARI)],
            'lokasi' => 'nullable|max:100',
        ]);
    }
}   