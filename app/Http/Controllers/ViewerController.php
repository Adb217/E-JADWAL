<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\{Guru, GuruPiket, Jadwal, Kelas, Mapel, Materi, MateriFile, Perubahan, Periode, Ruangan};

class ViewerController extends Controller
{
    private const REL = ['kelas', 'mapel', 'guru', 'ruangan'];

        public function mapelAngkatan()
    {
        $angkatan = Kelas::whereNotNull('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan')
            ->map(fn ($a) => ['angkatan' => $a, 'tingkat' => Kelas::hitungTingkat($a)])
            ->filter(fn ($a) => isset(Kelas::ROMAWI[$a['tingkat']]))
            ->values();

        return view('viewer.mapel.angkatan', [
            'angkatan' => $angkatan,
            'tahunAjaran' => Periode::aktif()?->tahun_ajaran,
        ]);
    }

    public function mapelKelas(int $angkatan)
    {
        $kelas = Kelas::where('angkatan', $angkatan)->orderBy('nama')->get();
        abort_if($kelas->isEmpty(), 404);

        return view('viewer.mapel.kelas', compact('kelas', 'angkatan'));
    }

       public function mapelMateri(Kelas $kelas)
    {
        $mapel = Jadwal::with(['mapel', 'guru'])->where('kelas_id', $kelas->id)->get()
            ->groupBy('mapel_id')
            ->map(fn ($g) => (object) [
                'mapel' => $g->first()->mapel,
                'guru' => $g->pluck('guru')->unique('id')->values(),
            ])
            ->sortBy(fn ($m) => $m->mapel->nama)->values();

        $materi = Materi::with('files')->whereIn('mapel_id', $mapel->pluck('mapel.id'))
            ->latest()->get()->groupBy('mapel_id');

        return view('viewer.mapel.materi', compact('kelas', 'mapel', 'materi'));
    }

    public function jadwalShow(Jadwal $jadwal)
    {
        $jadwal->load(self::REL);

        // tab: jadwal lain di kelas + hari yang sama (pekan ikut disaring)
        $pekan = $jadwal->pekan ?? Jadwal::pekanSekarang();
        $tabs = Jadwal::with('mapel')
            ->where('kelas_id', $jadwal->kelas_id)->where('hari', $jadwal->hari)
            ->where(fn ($q) => $q->whereNull('pekan')->orWhere('pekan', $pekan))
            ->urut()->get();

        $materi = Materi::with('files')->where('mapel_id', $jadwal->mapel_id)->latest()->get();

        return view('viewer.jadwal.show', compact('jadwal', 'materi', 'tabs'));
    }

    public function guru(Request $r)
    {
        $guru = Guru::with(['mapel', 'kehadiranHariIni'])->where('status', '!=', 'Nonaktif')
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('nama', 'ilike', "%$s%")
                ->orWhere('kode', 'ilike', "%$s%")
                ->orWhereHas('mapel', fn ($x) => $x->where('nama', 'ilike', "%$s%"))))
            ->orderBy('nama')->get();

        return view('viewer.guru.index', compact('guru'));
    }
    public function dashboard()
    {
        $hariIni = Jadwal::with(self::REL)->hariIni()->urut()->get();
        $today = $hariIni->reject(fn ($j) => $j->status === 'Selesai')->values();

        $total = Ruangan::count();
        $terpakai = $hariIni->where('status', 'Berlangsung')->pluck('ruangan_id')->unique()->count();
        $tersedia = max($total - $terpakai, 0);

        $piket = GuruPiket::with('guru')->where('hari', Jadwal::namaHariIni() ?? '-')->get();

        return view('viewer.dashboard', compact('today', 'terpakai', 'tersedia', 'piket'));
    }

    public function jadwal(Request $r)
    {
        $hari = $r->query('hari', 'today');

        $jadwal = Jadwal::with(self::REL)
            ->when($hari === 'today', fn ($q) => $q->hariIni())
            ->when(in_array($hari, Jadwal::HARI), fn ($q) => $q->where('hari', $hari))
            ->when($r->kelas_id, fn ($q, $v) => $q->where('kelas_id', $v))
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->whereHas('kelas', fn ($x) => $x->where('nama', 'ilike', "%$s%"))
                ->orWhereHas('mapel', fn ($x) => $x->where('nama', 'ilike', "%$s%"))
                ->orWhereHas('guru', fn ($x) => $x->where('nama', 'ilike', "%$s%"))))
            ->urut()->get();

        return view('viewer.jadwal.index', [
            'jadwal' => $jadwal,
            'kelas' => Kelas::orderBy('nama')->get(),
            'hari' => $hari,
        ]);
    }

    //     public function jadwalShow(Jadwal $jadwal)
    // {
    //     $jadwal->load(self::REL);
    //     $materi = Materi::with('files')->where('mapel_id', $jadwal->mapel_id)->latest()->get();

    //     return view('viewer.jadwal.show', compact('jadwal', 'materi'));
    // }

    public function materiDownload(MateriFile $file)
    {
        abort_unless(Storage::disk(MateriFile::DISK)->exists($file->path), 404, 'File tidak ditemukan.');

        return Storage::disk(MateriFile::DISK)->download($file->path, $file->nama_asli);
    }

    public function ruangan(Request $r)
    {
        $rooms = Ruangan::when($r->tipe, fn ($q, $t) => $q->where('tipe', $t))->orderBy('nama')->get();
        $today = Jadwal::with(['kelas', 'mapel', 'guru'])->hariIni()->urut()->get()->groupBy('ruangan_id');

        $rooms->each(function ($room) use ($today) {
            $list = $today->get($room->id, collect());
            $room->setRelation('sekarang', $list->firstWhere('status', 'Berlangsung'));
            $room->setRelation('berikutnya', $list->firstWhere('status', 'Akan Datang'));
        });

        return view('viewer.ruangan.index', ['rooms' => $rooms, 'tipe' => Ruangan::TIPE]);
    }

    public function ruanganShow(Ruangan $ruangan)
    {
        $jadwal = $ruangan->jadwals()->with(['kelas', 'mapel', 'guru'])->hariIni()->urut()->get();
        $sekarang = $jadwal->firstWhere('status', 'Berlangsung');

        return view('viewer.ruangan.show', compact('ruangan', 'jadwal', 'sekarang'));
    }

    public function perubahan()
    {
        return view('viewer.perubahan.index', ['changes' => Perubahan::latest()->take(20)->get()]);
    }
}