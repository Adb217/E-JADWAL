<div class="grid lg:grid-cols-2 gap-4 mb-4">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h2 class="font-bold text-lg border-b pb-2 mb-4">Detail Pelajaran</h2>
        <div class="space-y-4">
            <div>
                <label class="lbl">Kelas</label>
                <select name="kelas_id" class="inp" required>
                    <option value="">Pilih kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" @selected(old('kelas_id', $jadwal->kelas_id ?? null) == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="lbl">Mata Pelajaran</label>
                <select name="mapel_id" class="inp" required>
                    <option value="">Pilih mata pelajaran</option>
                    @foreach($mapel as $m)
                    <option value="{{ $m->id }}" @selected(old('mapel_id', $jadwal->mapel_id ?? null) == $m->id)>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="lbl">Guru Pengajar</label>
                <select name="guru_id" class="inp" required>
                    <option value="">Pilih guru</option>
                    @foreach($guru as $g)
                    <option value="{{ $g->id }}" @selected(old('guru_id', $jadwal->guru_id ?? null) == $g->id)>{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h2 class="font-bold text-lg border-b pb-2 mb-4">Waktu & Tempat</h2>
        <div class="space-y-4">
            <div>
                <label class="lbl">Hari</label>
                <select name="hari" class="inp" required>
                    @foreach(App\Models\Jadwal::HARI as $h)
                    <option @selected(old('hari', $jadwal->hari ?? null) === $h)>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="lbl">Jam Mulai</label>
                    <input type="time" name="jam_mulai" required class="inp" value="{{ old('jam_mulai', isset($jadwal) ? $jadwal->mulai : '07:00') }}">
                </div>
                <div>
                    <label class="lbl">Jam Selesai</label>
                    <input type="time" name="jam_selesai" required class="inp" value="{{ old('jam_selesai', isset($jadwal) ? $jadwal->selesai : '08:30') }}">
                </div>
            </div>
            <div>
                <label class="lbl">Ruangan</label>
                <select name="ruangan_id" class="inp" required>
                    <option value="">Pilih ruangan</option>
                    @foreach($ruangan as $r)
                    <option value="{{ $r->id }}" @selected(old('ruangan_id', $jadwal->ruangan_id ?? null) == $r->id)>{{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

@if(session('conflict'))
<div class="bg-red-50 border border-red-300 rounded-2xl p-4 mb-4 text-center">
    <p class="font-bold text-red-600 mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Jadwal Bentrok</p>
    <p class="text-sm text-red-500">{{ session('conflict') }}</p>
</div>
@else
<div class="bg-white rounded-2xl p-6 mb-4 text-center border border-gray-100">
    <div class="w-14 h-14 rounded-2xl bg-cream text-maroon flex items-center justify-center text-2xl mx-auto mb-3">
        <i class="fa-solid fa-calendar-check"></i>
    </div>
    <p class="text-gray-500 text-sm">Sistem otomatis memeriksa bentrok ruangan, guru, dan kelas saat disimpan.</p>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-4 text-sm text-red-600">
    <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
    <a href="{{ route('admin.jadwal.index') }}" class="btn-ghost">Batal</a>
    <button type="submit" class="btn"><i class="fa-solid fa-floppy-disk"></i>Simpan Jadwal</button>
</div>