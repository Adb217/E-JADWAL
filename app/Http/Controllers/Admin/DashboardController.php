<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Guru, Jadwal, Kelas, Perubahan, Ruangan};

class DashboardController extends Controller
{
    public function index()
    {
        $now = now()->format('H:i:s');

        $terpakai = Jadwal::hariIni()
            ->where('jam_mulai', '<=', $now)->where('jam_selesai', '>', $now)
            ->distinct()->count('ruangan_id');
        $totalRuangan = Ruangan::count();

        $stats = [
            ['fa-school', 'Total Kelas', Kelas::count(), 'bg-maroon/10 text-maroon'],
            ['fa-chalkboard-user', 'Total Guru', Guru::count(), 'bg-gold/20 text-yellow-700'],
            ['fa-circle-check', 'Ruang Terpakai', $terpakai, 'bg-blue-100 text-blue-600'],
            ['fa-circle-xmark', 'Ruang Kosong', max($totalRuangan - $terpakai, 0), 'bg-red-100 text-red-600'],
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'today' => Jadwal::with(['kelas', 'mapel'])->hariIni()->urut()->limit(8)->get(),
            'perubahan' => Perubahan::latest()->take(3)->get(),
        ]);
    }
}