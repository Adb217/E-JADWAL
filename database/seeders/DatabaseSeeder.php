<?php

namespace Database\Seeders;

use App\Models\{Guru, GuruPiket, Jadwal, Kelas, Mapel, Perubahan, Ruangan, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Admin Kosgoro',
            'password' => Hash::make('admin123'),
        ]);

        if (Jadwal::count() > 0) {
            return;
        }

        $kelas = collect(['X TKJ 1', 'X RPL 1', 'XI TKJ 2', 'XI RPL 2', 'XII RPL 2', 'X AKL 1'])
            ->map(fn ($n) => Kelas::create(['nama' => $n, 'jurusan' => explode(' ', $n)[1]]))->values();

        $mapel = collect(['Matematika', 'Pemrograman Web', 'Pemrograman Dasar', 'Administrasi Server', 'Pendidikan Jasmani', 'Bahasa Indonesia'])
            ->map(fn ($n) => Mapel::create(['nama' => $n]))->values();

        $guru = collect([
            ['Budi Santoso, S.Pd', '19800512 200501 1 003'],
            ['Siti Aminah, M.Kom', '19851120 201001 2 001'],
            ['Agus Riyanto, S.Pd', '19790101 200501 1 002'],
            ['Drs. Ahmad Yani', '19750310 200003 1 005'],
        ])->map(fn ($g) => Guru::create(['nama' => $g[0], 'nip' => $g[1]]))->values();

        $guru[0]->mapel()->sync([$mapel[0]->id]);
        $guru[1]->mapel()->sync([$mapel[1]->id, $mapel[2]->id]);
        $guru[2]->mapel()->sync([$mapel[4]->id]);
        $guru[3]->mapel()->sync([$mapel[5]->id]);

        $ruang = collect([
            ['R. 101', 'Kelas Reguler'], ['Lab Kom 1', 'Laboratorium Komputer'],
            ['Lab Kom 2', 'Laboratorium Komputer'], ['Lapangan A', 'Lainnya'],
        ])->map(fn ($r) => Ruangan::create(['nama' => $r[0], 'tipe' => $r[1]]))->values();

        $slots = [['07:00', '08:30'], ['08:30', '10:00'], ['10:15', '11:45']];
        foreach (array_slice(Jadwal::HARI, 0, 5) as $h => $hari) {
            foreach ($slots as $s => [$mulai, $selesai]) {
                foreach ([0, 1] as $i) {
                    Jadwal::create([
                        'hari' => $hari, 'jam_mulai' => $mulai, 'jam_selesai' => $selesai,
                        'kelas_id' => $kelas[($h + $s + $i * 2) % 6]->id,
                        'mapel_id' => $mapel[($h + $s + $i) % 6]->id,
                        'guru_id' => $guru[($h + $s + $i * 3) % 4]->id,
                        'ruangan_id' => $ruang[($s + $i * 2) % 4]->id,
                    ]);
                }
            }
            GuruPiket::create(['guru_id' => $guru[$h % 4]->id, 'hari' => $hari, 'lokasi' => 'Lobi Utama']);
        }

        Perubahan::create(['tipe' => 'guru_berhalangan', 'judul' => 'Guru Berhalangan', 'dari' => 'Bpk. Anton', 'ke' => 'Ibu Siti', 'alasan' => 'Izin sakit.', 'status' => 'Disetujui']);
        Perubahan::create(['tipe' => 'pindah_ruangan', 'judul' => 'Pindah Ruangan', 'dari' => 'Lab Kom 2', 'ke' => 'R. Teori 5', 'alasan' => 'Perbaikan AC.', 'status' => 'Menunggu']);
        Perubahan::create(['tipe' => 'tukar_jadwal', 'judul' => 'Tukar Jadwal', 'dari' => 'Penjaskes Senin', 'ke' => 'Penjaskes Selasa', 'alasan' => 'Lapangan dipakai upacara.', 'status' => 'Selesai']);
    }
}