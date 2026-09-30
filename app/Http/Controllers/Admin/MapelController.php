<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MapelController extends Controller
{
    public function index(Request $r)
    {
        $mapel = Mapel::withCount('jadwals')
            ->when($r->q, fn ($q, $s) => $q->where('nama', 'ilike', "%$s%"))
            ->orderBy('nama')->get();

        return view('admin.mapel.index', compact('mapel'));
    }

    public function store(Request $r)
    {
        Mapel::create($this->valid($r));
        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $r, Mapel $mapel)
    {
        $mapel->update($this->valid($r, $mapel->id));
        return back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return back()->with('success', 'Mata pelajaran dihapus.');
    }

    private function valid(Request $r, $id = null): array
    {
        return $r->validate([
            'nama' => ['required', 'max:100', Rule::unique('mapels', 'nama')->ignore($id)],
            'kode' => 'nullable|max:20',
        ]);
    }
}