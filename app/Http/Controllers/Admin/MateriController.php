<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\MateriFile;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    /** Bebas semua tipe file, kecuali yang bisa dieksekusi. */
    private const BLOKIR = ['php', 'phtml', 'phar', 'exe', 'bat', 'cmd', 'sh', 'msi', 'js'];

    public function index(Request $r)
    {
        $materi = Materi::with(['mapel', 'files'])
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('judul', 'ilike', "%$s%")
                ->orWhereHas('mapel', fn ($x) => $x->where('nama', 'ilike', "%$s%"))))
            ->latest()->get();

        return view('admin.materi.index', [
            'materi' => $materi,
            'mapel' => Mapel::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function store(Request $r)
    {
        $d = $this->valid($r, true);
        $materi = Materi::create(collect($d)->only(['mapel_id', 'judul', 'deskripsi'])->all());
        $this->simpanFile($materi, $r);

        return back()->with('success', 'Materi berhasil ditambahkan.');
    }

    public function update(Request $r, Materi $materi)
    {
        $d = $this->valid($r);

        $hapus = $materi->files()->whereIn('id', $r->input('hapus_file', []))->get();
        $sisa = $materi->files()->count() - $hapus->count() + count($r->file('files', []));
        if ($sisa < 1) {
            return back()->withErrors(['files' => 'Materi minimal harus punya 1 file.']);
        }

        $materi->update(collect($d)->only(['mapel_id', 'judul', 'deskripsi'])->all());
        $hapus->each->delete();
        $this->simpanFile($materi, $r);

        return back()->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();

        return back()->with('success', 'Materi dihapus.');
    }

    private function simpanFile(Materi $materi, Request $r): void
    {
        foreach ($r->file('files', []) as $f) {
            $ukuran = $f->getSize();
            $mime = $f->getClientMimeType();
            $nama = $f->getClientOriginalName();

            $materi->files()->create([
                'path' => $f->store("materi/{$materi->id}", MateriFile::DISK),
                'nama_asli' => $nama,
                'mime' => $mime,
                'ukuran' => $ukuran,
            ]);
        }
    }

    private function valid(Request $r, bool $wajibFile = false): array
    {
        return $r->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'judul' => 'required|max:150',
            'deskripsi' => 'nullable|max:1000',
            'files' => [$wajibFile ? 'required' : 'nullable', 'array', 'max:10'],
            'files.*' => [
                'file', 'max:10240',
                function ($attr, $file, $fail) {
                    if (in_array(strtolower($file->getClientOriginalExtension()), self::BLOKIR)) {
                        $fail('Tipe file .' . $file->getClientOriginalExtension() . ' tidak diizinkan.');
                    }
                },
            ],
            'hapus_file' => 'nullable|array',
            'hapus_file.*' => 'integer',
        ], [
            'files.required' => 'Pilih minimal 1 file.',
            'files.max' => 'Maksimal 10 file sekali upload.',
            'files.*.max' => 'Ukuran tiap file maksimal 10 MB.',
            'files.*.uploaded' => 'File gagal diupload. Cek ukurannya (maks 10 MB per file).',
        ]);
    }
}