<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perubahan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerubahanController extends Controller
{
    public function index()
    {
        return view('admin.perubahan.index', ['perubahan' => Perubahan::latest()->get()]);
    }

    public function store(Request $r)
    {
        Perubahan::create($this->valid($r));
        return back()->with('success', 'Info perubahan berhasil ditambahkan.');
    }

    public function update(Request $r, Perubahan $perubahan)
    {
        $perubahan->update($this->valid($r));
        return back()->with('success', 'Info perubahan berhasil diperbarui.');
    }

    public function destroy(Perubahan $perubahan)
    {
        $perubahan->delete();
        return back()->with('success', 'Info perubahan dihapus.');
    }

    private function valid(Request $r): array
    {
        return $r->validate([
            'tipe' => ['required', Rule::in(array_keys(Perubahan::TIPE))],
            'judul' => 'required|max:120',
            'dari' => 'nullable|max:120',
            'ke' => 'nullable|max:120',
            'alasan' => 'required|max:500',
            'status' => ['required', Rule::in(Perubahan::STATUS)],
        ]);
    }
}