<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Periode;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataNyataSeeder extends Seeder
{
    // T=TKJ P=PSPT D=DKV R=RPL K=KKR
    private const KELAS = [
        'T' => ['XII TKJ', 'Teknik Komputer dan Jaringan'],
        'P' => ['XII PSPT', 'Produksi Siaran Pertelevisian'],
        'D' => ['XII DKV', 'Desain Komunikasi Visual'],
        'R' => ['XII RPL', 'Rekayasa Perangkat Lunak'],
        'K' => ['XII KKR', 'Kecantikan Kulit dan Rambut'],
    ];

    private const GURU = [
        'KH' => 'Kharisma Larasyudha, S.Kom',
        'MF' => 'Muhammad Farhan',
        'MRS' => 'Marselina, S.Ikom.',
        'AJ' => 'Achmad Jaenudin',
        'LDF' => 'Laras Dwi Febriani',
        'EIJ' => 'Eren Intan, S.Pd.',
        'NSM' => 'Nur Siti Masrofijah',
        'VR' => 'Vriski Aviandika, S.Pd.',
        'MRD' => 'Mardiah, S.Pd.',
        'IZF' => 'Ikhsan Zen Fadli, S.Pd.',
        'TUP' => 'Tun Utama Putra, M.Pd',
        'ENR' => 'Erfina Nurfitria Rifani, S.Pd',
        'FM' => 'Fetry Mirasari, S.Pd.I',
        'FQ' => 'Furqon Maulana, S.Kom.',
        'MPS' => 'Maya Puspa Sartika, S.Psi',
        'LHA' => 'Lukman Hakim Anshori, Lc.',
        'AS' => 'Ahmad Subandi, S.E., M.M.',
        'ADA' => 'Agista Deva Ananda, S.Pd',
        'NL' => 'Nunyk Laila, S.Pd.',
        'MAJ' => 'Maulana Adi Juliawan',
        'HLH' => 'Holilah R., M.Pd.',
        'NOY' => 'Dhama Shinta Noya Azzahra, S.Pd',
    ];

    private const MAPEL = [
        'TKJ' => 'Produktif TKJ',
        'PSPT' => 'Produktif PSPT',
        'DKV' => 'Produktif DKV',
        'RPL' => 'Produktif RPL',
        'KKR' => 'Produktif KKR',
        'B ING' => 'Bahasa Inggris',
        'B ING TL' => 'Bahasa Inggris Tingkat Lanjut',
        'B INDO' => 'Bahasa Indonesia',
        'MTK' => 'Matematika',
        'JPN' => 'Bahasa Jepang',
        'PKN' => 'Pendidikan Kewarganegaraan',
        'PAI' => 'Pendidikan Agama Islam',
        'SBD' => 'Seni Budaya',
        'DM' => 'Digital Marketing',
        'KIK' => 'Kreatif Inovasi Kewirausahaan',
        'KWU' => 'Kewirausahaan',
        'BTQ' => 'Baca Tulis Quran',
        'BK' => 'Bimbingan Konseling',
        'PJOK' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
    ];

    private const RUANGAN = [
        'REG. 6' => 'Kelas Reguler',
        'REG. 7' => 'Kelas Reguler',
        'REG. 8' => 'Kelas Reguler',
        'REG. 12' => 'Kelas Reguler',
        'LAB TKJ' => 'Laboratorium Komputer',
        'LAB DKV' => 'Laboratorium Komputer',
        'LAB 1 RPL' => 'Laboratorium Komputer',
        'LAB 2 RPL' => 'Laboratorium Komputer',
        'STUDIO PSPT' => 'Lainnya',
        'LAB KKR' => 'Lainnya',
        'LAP. OLAH RAGA' => 'Lainnya',
    ];

    private const PIKET = ['Senin' => 'ENR', 'Selasa' => 'NOY', 'Rabu' => 'LHA', 'Kamis' => 'MRD', 'Jumat' => 'FM'];

    // nomor jam => [mulai, selesai]. 11 = Sesi 1, 12 = Sesi 2
    private const JAM = [
        1 => ['07:15', '07:50'], 2 => ['07:50', '08:25'], 3 => ['08:25', '09:00'], 4 => ['09:00', '09:35'],
        5 => ['10:00', '10:30'], 6 => ['10:30', '11:00'], 7 => ['11:00', '11:30'], 8 => ['11:30', '12:00'],
        9 => ['12:40', '13:15'], 10 => ['13:15', '13:50'], 11 => ['14:00', '15:00'], 12 => ['15:00', '16:00'],
    ];

    private const JAM_JUMAT = [
        5 => ['10:00', '10:35'], 6 => ['10:35', '11:10'],
        7 => ['12:50', '13:25'], 8 => ['13:25', '14:00'], 9 => ['14:00', '14:35'], 10 => ['14:35', '15:10'],
    ] + self::JAM;

    public function run(): void
    {
        DB::transaction(function () {
            $this->bersihkanDummy();

            $periode = $this->periode();
            $kelas = $this->kelas();
            $guru = $this->guru();
            $mapel = $this->mapel();
            $ruangan = $this->ruangan();

            $this->jadwal($periode, $kelas, $guru, $mapel, $ruangan);
            $this->piket($guru);
        });
    }

    /** Cuma jalan di run pertama (belum ada guru yang punya kode). */
    private function bersihkanDummy(): void
    {
        if (Guru::whereNotNull('kode')->exists()) {
            return;
        }

        DB::table('perubahans')->delete();
        DB::table('guru_pikets')->delete();
        DB::table('jadwals')->delete();
        DB::table('kelas')->delete();
        Mapel::all()->each->delete();   // lewat Eloquent biar file materinya ikut kehapus
        DB::table('guru_mapel')->delete();
        DB::table('gurus')->delete();
        DB::table('ruangans')->delete();
    }

    private function periode(): Periode
    {
        $p = Periode::aktif() ?? new Periode(['aktif' => true]);
        $p->fill(['nama' => 'Ganjil 2026/2027 (draft v4)', 'tahun_ajaran' => '2026/2027'])->save();
        Periode::lupakan();

        return $p;
    }

    private function kelas(): array
    {
        $out = [];
        foreach (self::KELAS as $huruf => [$nama, $jurusan]) {
            $out[$huruf] = Kelas::updateOrCreate(['nama' => $nama], ['jurusan' => $jurusan, 'angkatan' => 13])->id;
        }

        return $out;
    }

    private function guru(): array
    {
        $out = [];
        foreach (self::GURU as $kode => $nama) {
            // firstOrCreate: nama yang udah lo edit di admin nggak ketimpa
            $out[$kode] = Guru::firstOrCreate(['kode' => $kode], ['nama' => $nama, 'status' => 'Aktif'])->id;
        }

        return $out;
    }

    private function mapel(): array
    {
        $out = [];
        foreach (self::MAPEL as $kode => $nama) {
            $out[$kode] = Mapel::updateOrCreate(['kode' => $kode], ['nama' => $nama])->id;
        }

        return $out;
    }

    private function ruangan(): array
    {
        $out = [];
        foreach (self::RUANGAN as $nama => $tipe) {
            $out[$nama] = Ruangan::firstOrCreate(['nama' => $nama], ['tipe' => $tipe])->id;
        }

        return $out;
    }

    private function jadwal(Periode $p, array $kelas, array $guru, array $mapel, array $ruangan): void
    {
        DB::table('jadwals')->where('periode_id', $p->id)->delete();

        $ambil = function (array $arr, string $key, string $jenis) {
            return $arr[$key] ?? throw new \RuntimeException("Kode $jenis '$key' nggak ada di daftar.");
        };

        $now = now();
        $rows = [];
        $pivot = [];

        foreach ($this->data() as $hari => $blok) {
            $jam = $hari === 'Jumat' ? self::JAM_JUMAT : self::JAM;

            foreach ($blok as $r) {
                [$a, $b, $huruf, $mp, $gr, $rg] = $r;
                $pekan = $r[6] ?? null;

                foreach (str_split($huruf) as $k) {
                    $rows[] = [
                        'periode_id' => $p->id,
                        'kelas_id' => $ambil($kelas, $k, 'kelas'),
                        'mapel_id' => $ambil($mapel, $mp, 'mapel'),
                        'guru_id' => $ambil($guru, $gr, 'guru'),
                        'ruangan_id' => $rg ? $ambil($ruangan, $rg, 'ruangan') : null,
                        'hari' => $hari,
                        'jam_mulai' => $jam[$a][0],
                        'jam_selesai' => $jam[$b][1],
                        'pekan' => $pekan,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                $pivot[$guru[$gr]][$mapel[$mp]] = true;
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('jadwals')->insert($chunk);
        }

        // isi relasi guru <-> mapel dari jadwal (buat chip mapel di halaman Guru)
        foreach ($pivot as $guruId => $mapelIds) {
            Guru::find($guruId)->mapel()->syncWithoutDetaching(array_keys($mapelIds));
        }

        $this->command?->info(count($rows) . ' baris jadwal dimasukkan.');
    }

    private function piket(array $guru): void
    {
        foreach (self::PIKET as $hari => $kode) {
            if (! DB::table('guru_pikets')->where('hari', $hari)->exists()) {
                DB::table('guru_pikets')->insert([
                    'guru_id' => $guru[$kode], 'hari' => $hari, 'lokasi' => null,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * [jam_awal, jam_akhir, kelas(huruf), kode_mapel, kode_guru, ruangan|null, pekan?]
     * Jam 11 = Sesi 1, 12 = Sesi 2 (ruangan null). Pekan: '1-2' / '3-4' / kosong = tiap pekan.
     */
    private function data(): array
    {
        return [
            'Senin' => [
                [1, 4, 'T', 'TKJ', 'KH', 'LAB TKJ'],
                [5, 6, 'T', 'TKJ', 'KH', 'LAB TKJ'],
                [1, 2, 'P', 'PSPT', 'MF', 'STUDIO PSPT'],
                [3, 4, 'P', 'PSPT', 'MRS', 'STUDIO PSPT'],
                [5, 6, 'P', 'PSPT', 'MRS', 'STUDIO PSPT'],
                [1, 4, 'D', 'DKV', 'AJ', 'LAB DKV'],
                [5, 6, 'D', 'DKV', 'AJ', 'LAB DKV'],
                [1, 4, 'R', 'RPL', 'LDF', 'LAB 1 RPL'],
                [5, 6, 'R', 'RPL', 'LDF', 'LAB 1 RPL'],
                [1, 4, 'K', 'KKR', 'EIJ', 'LAB KKR'],
                [5, 6, 'K', 'B ING TL', 'EIJ', 'REG. 12'],
                [7, 8, 'K', 'DM', 'AS', 'REG. 12'],
                [9, 10, 'K', 'B ING', 'MRD', 'REG. 12'],
                [7, 8, 'TP', 'JPN', 'ADA', 'REG. 7'],
                [9, 10, 'TP', 'SBD', 'NL', 'REG. 7'],
                [7, 8, 'D', 'DKV', 'MF', 'REG. 8'],
                [9, 10, 'D', 'DM', 'AS', 'REG. 8'],
                [7, 8, 'R', 'PKN', 'NSM', 'REG. 6'],
                [9, 10, 'R', 'B INDO', 'VR', 'REG. 6'],
                // Sesi pekan 1-2
                [11, 11, 'TP', 'KWU', 'AS', null, '1-2'],
                [11, 11, 'D', 'B INDO', 'VR', null, '1-2'],
                [11, 11, 'R', 'B ING', 'MRD', null, '1-2'],
                [11, 11, 'K', 'MTK', 'IZF', null, '1-2'],
                [12, 12, 'TP', 'B INDO', 'VR', null, '1-2'],
                [12, 12, 'D', 'MTK', 'IZF', null, '1-2'],
                [12, 12, 'R', 'KWU', 'AS', null, '1-2'],
                [12, 12, 'K', 'B ING', 'MRD', null, '1-2'],
                // Sesi pekan 3-4
                [11, 11, 'T', 'TKJ', 'FQ', null, '3-4'],
                [11, 11, 'P', 'PSPT', 'MRS', null, '3-4'],
                [11, 11, 'D', 'DKV', 'AJ', null, '3-4'],
                [11, 11, 'R', 'RPL', 'LDF', null, '3-4'],
                [11, 11, 'K', 'KKR', 'ENR', null, '3-4'],
                [12, 12, 'TP', 'B INDO', 'VR', null, '3-4'],
                [12, 12, 'D', 'B INDO', 'NL', null, '3-4'],
                [12, 12, 'R', 'B ING', 'EIJ', null, '3-4'],
                [12, 12, 'K', 'MTK', 'IZF', null, '3-4'],
            ],

            'Selasa' => [
                [1, 4, 'T', 'TKJ', 'KH', 'LAB TKJ'],
                [1, 4, 'P', 'PSPT', 'MRS', 'STUDIO PSPT'],
                [1, 4, 'D', 'DKV', 'MF', 'LAB DKV'],
                [1, 4, 'R', 'RPL', 'MAJ', 'LAB 1 RPL'],
                [1, 2, 'K', 'PKN', 'NSM', 'REG. 12'],
                [3, 4, 'K', 'PAI', 'LHA', 'REG. 12'],
                [5, 5, 'K', 'PAI', 'LHA', 'REG. 12'],
                [6, 8, 'K', 'KIK', 'AS', 'REG. 12'],
                [9, 10, 'K', 'BTQ', 'FM', 'REG. 12'],
                [5, 6, 'TP', 'MTK', 'IZF', 'REG. 7'],
                [7, 8, 'TP', 'B ING', 'MRD', 'REG. 7'],
                [9, 10, 'TP', 'B ING TL', 'EIJ', 'REG. 7'],
                [5, 8, 'D', 'DKV', 'AJ', 'LAB DKV'],
                [9, 10, 'D', 'MTK', 'IZF', 'REG. 8'],
                [5, 6, 'R', 'B ING TL', 'MRD', 'REG. 6'],
                [7, 8, 'R', 'MTK', 'IZF', 'REG. 6'],
                [9, 10, 'R', 'SBD', 'NL', 'REG. 6'],
                // Sesi pekan 1-2
                [11, 11, 'TP', 'B ING', 'MRD', null, '1-2'],
                [11, 11, 'D', 'KWU', 'AS', null, '1-2'],
                [11, 11, 'R', 'MTK', 'HLH', null, '1-2'],
                [11, 11, 'K', 'B INDO', 'VR', null, '1-2'],
                [12, 12, 'TP', 'MTK', 'HLH', null, '1-2'],
                [12, 12, 'D', 'B INDO', 'NL', null, '1-2'],
                [12, 12, 'R', 'B ING', 'EIJ', null, '1-2'],
                [12, 12, 'K', 'KWU', 'AS', null, '1-2'],
                // Sesi pekan 3-4
                [11, 11, 'TP', 'B ING', 'MRD', null, '3-4'],
                [11, 11, 'D', 'MTK', 'HLH', null, '3-4'],
                [11, 11, 'R', 'B INDO', 'NL', null, '3-4'],
                [11, 11, 'K', 'B ING', 'EIJ', null, '3-4'],
                [12, 12, 'TP', 'MTK', 'IZF', null, '3-4'],
                [12, 12, 'D', 'B ING', 'EIJ', null, '3-4'],
                [12, 12, 'R', 'MTK', 'HLH', null, '3-4'],
                [12, 12, 'K', 'B INDO', 'VR', null, '3-4'],
            ],

            'Rabu' => [
                [1, 2, 'TPD', 'PJOK', 'TUP', 'LAP. OLAH RAGA'],
                [1, 2, 'R', 'DM', 'AS', 'REG. 6'],
                [1, 2, 'K', 'B INDO', 'VR', 'REG. 12'],
                [3, 4, 'TP', 'BTQ', 'FM', 'REG. 7'],
                [3, 4, 'D', 'KIK', 'AS', 'REG. 8'],
                [3, 4, 'RK', 'PJOK', 'TUP', 'LAP. OLAH RAGA'],
                [5, 8, 'T', 'TKJ', 'KH', 'LAB TKJ'],
                [5, 8, 'P', 'PSPT', 'MRS', 'STUDIO PSPT'],
                [5, 5, 'D', 'KIK', 'AS', 'LAB DKV'],
                [6, 8, 'D', 'DKV', 'AJ', 'LAB DKV'],
                [5, 6, 'R', 'RPL', 'MAJ', 'LAB 1 RPL'],
                [7, 8, 'R', 'RPL', 'LDF', 'LAB 1 RPL'],
                [5, 8, 'K', 'KKR', 'ENR', 'REG. 12'],
                [9, 10, 'T', 'TKJ', 'KH', 'LAB TKJ'],
                [9, 10, 'P', 'PSPT', 'MF', 'STUDIO PSPT'],
                [9, 10, 'D', 'B ING TL', 'EIJ', 'REG. 8'],
                [9, 10, 'R', 'RPL', 'LDF', 'LAB 1 RPL'],
                [9, 10, 'K', 'KKR', 'ENR', 'REG. 12'],
                // Sesi pekan 1-2
                [11, 11, 'TP', 'B INDO', 'NL', null, '1-2'],
                [11, 11, 'D', 'B ING', 'EIJ', null, '1-2'],
                [11, 11, 'R', 'KWU', 'AS', null, '1-2'],
                [11, 11, 'K', 'MTK', 'IZF', null, '1-2'],
                [12, 12, 'TP', 'KWU', 'AS', null, '1-2'],
                [12, 12, 'D', 'MTK', 'HLH', null, '1-2'],
                [12, 12, 'R', 'B INDO', 'VR', null, '1-2'],
                [12, 12, 'K', 'B ING', 'EIJ', null, '1-2'],
                // Sesi pekan 3-4
                [11, 11, 'TP', 'B INDO', 'NL', null, '3-4'],
                [11, 11, 'D', 'B INDO', 'VR', null, '3-4'],
                [11, 11, 'R', 'B ING', 'MRD', null, '3-4'],
                [11, 11, 'K', 'MTK', 'IZF', null, '3-4'],
                [12, 12, 'T', 'TKJ', 'FQ', null, '3-4'],
                [12, 12, 'P', 'PSPT', 'MRS', null, '3-4'],
                [12, 12, 'D', 'DKV', 'AJ', null, '3-4'],
                [12, 12, 'R', 'RPL', 'LDF', null, '3-4'],
                [12, 12, 'K', 'KKR', 'ENR', null, '3-4'],
            ],

            'Kamis' => [
                [1, 3, 'TP', 'KIK', 'AS', 'REG. 7'],
                [4, 4, 'TP', 'PAI', 'LHA', 'REG. 7'],
                [1, 2, 'D', 'SBD', 'NL', 'REG. 8'],
                [3, 4, 'D', 'PKN', 'NSM', 'REG. 8'],
                [1, 3, 'R', 'PAI', 'LHA', 'REG. 6'],
                [4, 4, 'R', 'KIK', 'AS', 'REG. 6'],
                [1, 4, 'K', 'KKR', 'ENR', 'LAB KKR'],
                [5, 6, 'TP', 'PAI', 'LHA', 'REG. 7'],
                [7, 8, 'TP', 'B INDO', 'VR', 'REG. 7'],
                [9, 10, 'TP', 'PKN', 'NSM', 'REG. 7'],
                [5, 6, 'D', 'JPN', 'ADA', 'REG. 8'],
                [7, 8, 'D', 'BK', 'MPS', 'REG. 8'],
                [9, 10, 'D', 'BTQ', 'FM', 'REG. 8'],
                [5, 6, 'R', 'KIK', 'AS', 'REG. 6'],
                [7, 8, 'R', 'BTQ', 'FM', 'REG. 6'],
                [9, 10, 'R', 'JPN', 'ADA', 'REG. 6'],
                [5, 6, 'K', 'KKR', 'ENR', 'LAB KKR'],
                [7, 8, 'K', 'SBD', 'NL', 'REG. 12'],
                [9, 10, 'K', 'BK', 'MPS', 'REG. 12'],
                // Sesi pekan 1-2
                [11, 11, 'TP', 'MTK', 'IZF', null, '1-2'],
                [11, 11, 'D', 'B ING', 'MRD', null, '1-2'],
                [11, 11, 'R', 'B INDO', 'VR', null, '1-2'],
                [11, 11, 'K', 'MTK', 'IZF', null, '1-2'],
                [12, 12, 'TP', 'B ING', 'MRD', null, '1-2'],
                [12, 12, 'D', 'KWU', 'AS', null, '1-2'],
                [12, 12, 'R', 'MTK', 'HLH', null, '1-2'],
                [12, 12, 'K', 'B INDO', 'NL', null, '1-2'],
                // Sesi pekan 3-4
                [11, 11, 'T', 'TKJ', 'FQ', null, '3-4'],
                [11, 11, 'P', 'PSPT', 'MRS', null, '3-4'],
                [11, 11, 'D', 'DKV', 'AJ', null, '3-4'],
                [11, 11, 'R', 'RPL', 'LDF', null, '3-4'],
                [11, 11, 'K', 'KKR', 'ENR', null, '3-4'],
                [12, 12, 'TP', 'B ING', 'MRD', null, '3-4'],
                [12, 12, 'D', 'B INDO', 'NL', null, '3-4'],
                [12, 12, 'R', 'MTK', 'IZF', null, '3-4'],
                [12, 12, 'K', 'MTK', 'HLH', null, '3-4'],
            ],

            // Jumat: jam beda (lihat JAM_JUMAT), nggak ada Sesi
            'Jumat' => [
                [1, 2, 'TP', 'BK', 'MPS', 'REG. 7'],
                [3, 4, 'TP', 'DM', 'MRD', 'REG. 7'],
                [5, 6, 'T', 'TKJ', 'FQ', 'LAB TKJ'],
                [7, 10, 'T', 'TKJ', 'FQ', 'LAB TKJ'],
                [5, 6, 'P', 'PSPT', 'MRS', 'LAB 2 RPL'],
                [7, 8, 'P', 'PSPT', 'MF', 'LAB 2 RPL'],
                [9, 10, 'P', 'PSPT', 'MRS', 'LAB 2 RPL'],
                [1, 3, 'D', 'PAI', 'LHA', 'REG. 8'],
                [4, 4, 'D', 'B ING', 'EIJ', 'REG. 8'],
                [5, 5, 'D', 'B ING', 'EIJ', 'REG. 8'],
                [6, 6, 'D', 'DKV', 'AJ', 'LAB DKV'],
                [7, 10, 'D', 'DKV', 'AJ', 'LAB DKV'],
                [1, 2, 'R', 'B ING', 'MRD', 'REG. 6'],
                [3, 4, 'R', 'BK', 'MPS', 'REG. 6'],
                [5, 6, 'R', 'RPL', 'LDF', 'LAB 1 RPL'],
                [7, 10, 'R', 'RPL', 'LDF', 'LAB 1 RPL'],
                [1, 4, 'K', 'KKR', 'ENR', 'LAB KKR'],
                [5, 6, 'K', 'KKR', 'ENR', 'LAB KKR'],
                [7, 8, 'K', 'JPN', 'ADA', 'REG. 12'],
                [9, 10, 'K', 'MTK', 'IZF', 'REG. 12'],
            ],
        ];
    }
}
