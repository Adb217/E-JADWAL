<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Guru, Mapel};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $r)
    {
        $guru = Guru::with('mapel')
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('nama', 'ilike', "%$s%")->orWhere('nip', 'ilike', "%$s%")))
            ->orderBy('nama')->get();

        return view('admin.guru.index', ['guru' => $guru, 'mapel' => Mapel::orderBy('nama')->get()]);
    }

    public function store(Request $r)
    {
        $this->save($r, new Guru);
        return back()->with('success', 'Guru berhasil ditambahkan.');
    }

    public function update(Request $r, Guru $guru)
    {
        $this->save($r, $guru);
        return back()->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return back()->with('success', 'Guru dihapus.');
    }

    private function save(Request $r, Guru $guru): void
    {
        $d = $r->validate([
            'nama' => 'required|max:120',
            'nip' => ['nullable', 'max:30', Rule::unique('gurus', 'nip')->ignore($guru->id)],
            'status' => ['required', Rule::in(['Aktif', 'Cuti', 'Nonaktif'])],
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mapels,id',
        ]);

        $guru->fill(Arr::except($d, 'mapel_ids'))->save();
        $guru->mapel()->sync($d['mapel_ids'] ?? []);
    }
}