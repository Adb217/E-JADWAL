<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    public function index(Request $r)
    {
        $ruangan = Ruangan::withCount('jadwals')
            ->when($r->q, fn ($q, $s) => $q->where('nama', 'ilike', "%$s%"))
            ->orderBy('nama')->get();

        return view('admin.ruangan.index', compact('ruangan'));
    }

    public function store(Request $r)
    {
        Ruangan::create($this->valid($r));
        return back()->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function update(Request $r, Ruangan $ruangan)
    {
        $ruangan->update($this->valid($r, $ruangan->id));
        return back()->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return back()->with('success', 'Ruangan dihapus.');
    }

    private function valid(Request $r, $id = null): array
    {
        return $r->validate([
            'nama' => ['required', 'max:100', Rule::unique('ruangans', 'nama')->ignore($id)],
            'tipe' => ['required', Rule::in(Ruangan::TIPE)],
            'kapasitas' => 'nullable|integer|min:1|max:1000',
        ]);
    }
}