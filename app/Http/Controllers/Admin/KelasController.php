<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index(Request $r)
    {
        $kelas = Kelas::withCount('jadwals')
            ->when($r->q, fn ($q, $s) => $q->where('nama', 'ilike', "%$s%"))
            ->orderBy('nama')->get();

        return view('admin.kelas.index', compact('kelas'));
    }

    public function store(Request $r)
    {
        Kelas::create($this->valid($r));
        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $r, Kelas $kelas)
    {
        $kelas->update($this->valid($r, $kelas->id));
        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return back()->with('success', 'Kelas dihapus.');
    }

    private function valid(Request $r, $id = null): array
    {
        return $r->validate([
            'nama' => ['required', 'max:50', Rule::unique('kelas', 'nama')->ignore($id)],
            'jurusan' => 'nullable|max:100',
        ]);
    }
}