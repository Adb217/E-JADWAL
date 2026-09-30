<?php

namespace App\Http\Controllers;

use App\Models\{GuruPiket, Jadwal, Kelas, Perubahan, Ruangan};
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    private const REL = ['kelas', 'mapel', 'guru', 'ruangan'];

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