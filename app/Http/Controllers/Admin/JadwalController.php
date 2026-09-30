<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Guru, Jadwal, Kelas, Mapel, Ruangan};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    public function index(Request $r)
    {
        $jadwal = Jadwal::with(['kelas', 'mapel', 'guru', 'ruangan'])
            ->when($r->hari, fn ($q, $v) => $q->where('hari', $v))
            ->when($r->kelas_id, fn ($q, $v) => $q->where('kelas_id', $v))
            ->urut()->get();

        return view('admin.jadwal.index', ['jadwal' => $jadwal, 'kelas' => Kelas::orderBy('nama')->get()]);
    }

    public function create() { return view('admin.jadwal.create', $this->opsi()); }

    public function store(Request $r)
    {
        $d = $r->validate($this->rules());
        if ($msg = $this->bentrok($d)) {
            return back()->withInput()->with('conflict', $msg);
        }
        Jadwal::create($d);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil disimpan.');
    }

    public function edit(Jadwal $jadwal) { return view('admin.jadwal.edit', $this->opsi() + compact('jadwal')); }

    public function update(Request $r, Jadwal $jadwal)
    {
        $d = $r->validate($this->rules());
        if ($msg = $this->bentrok($d, $jadwal->id)) {
            return back()->withInput()->with('conflict', $msg);
        }
        $jadwal->update($d);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return back()->with('success', 'Jadwal dihapus.');
    }

    private function rules(): array
    {
        return [
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:gurus,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'hari' => ['required', Rule::in(Jadwal::HARI)],
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ];
    }

    private function opsi(): array
    {
        return [
            'kelas' => Kelas::orderBy('nama')->get(),
            'mapel' => Mapel::orderBy('nama')->get(),
            'guru' => Guru::orderBy('nama')->get(),
            'ruangan' => Ruangan::orderBy('nama')->get(),
        ];
    }

    private function bentrok(array $d, ?int $ignoreId = null): ?string
    {
        $b = Jadwal::bentrok($d, $ignoreId)->with(['kelas', 'guru', 'ruangan'])->first();
        if (! $b) {
            return null;
        }

        $s = [];
        if ($b->ruangan_id == $d['ruangan_id']) $s[] = 'ruangan ' . $b->ruangan->nama;
        if ($b->guru_id == $d['guru_id']) $s[] = 'guru ' . $b->guru->nama;
        if ($b->kelas_id == $d['kelas_id']) $s[] = 'kelas ' . $b->kelas->nama;

        return "Bentrok dengan jadwal {$b->mulai} - {$b->selesai}: " . implode(', ', $s) . ' sudah terpakai pada jam tersebut.';
    }
}