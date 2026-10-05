@extends('layouts.admin')
@section('title','Materi')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={mapel_id:'', judul:'', deskripsi:'', files:[]}; this.open=true },
    edit(m){ this.editing=m.id; this.f={...m, deskripsi:m.deskripsi ?? ''}; this.open=true } }">

    <x-page-head title="Materi Pelajaran" sub="Upload materi per mata pelajaran. Viewer bisa download dari detail jadwal." add="Tambah Materi" search="Cari judul atau mapel..." />

    <div class="grid gap-4 md:grid-cols-2">
        @forelse($materi as $m)
        <div class="card border-l-4 border-l-gold">
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-semibold leading-snug">{{ $m->judul }}</h3>
                <span class="chip whitespace-nowrap"><i class="fa-solid fa-book-open text-[10px]"></i>{{ $m->mapel->nama }}</span>
            </div>
            @if($m->deskripsi)<p class="mt-1.5 text-sm text-gray-500">{{ $m->deskripsi }}</p>@endif
            <p class="mt-2 text-xs text-gray-400"><i class="fa-regular fa-clock mr-1"></i>Diupload {{ $m->created_at->translatedFormat('l, d F Y · H:i') }}</p>

            <ul class="mt-3 space-y-1.5">
                @foreach($m->files as $f)
                <li>
                    <a href="{{ route('viewer.materi.download', $f) }}"
                       class="flex items-center gap-3 rounded-lg border border-line px-3 py-2 text-sm transition hover:border-maroon/40 hover:text-maroon">
                        <i class="fa-regular {{ $f->icon }} w-4 text-center text-maroon"></i>
                        <span class="min-w-0 flex-1 truncate">{{ $f->nama_asli }}</span>
                        <span class="text-xs text-gray-400">{{ $f->ukuran_teks }}</span>
                    </a>
                </li>
                @endforeach
            </ul>

            <hr class="my-3">
            <x-actions :item="$m->only(['id','mapel_id','judul','deskripsi']) + ['files' => $m->files->map->only(['id','nama_asli'])->all()]"
                       :destroy="route('admin.materi.destroy', $m)"
                       msg="Hapus materi ini beserta semua filenya?" />
        </div>
        @empty
        <x-empty-state icon="fa-folder-open" text="Belum ada materi." />
        @endforelse
    </div>

    <x-modal title="Materi" :store="route('admin.materi.store')" :update="route('admin.materi.update','_id_')">
        <div>
            <label class="lbl">Mata Pelajaran</label>
            <select name="mapel_id" x-model="f.mapel_id" required class="inp">
                <option value="" disabled>Pilih mapel</option>
                @foreach($mapel as $mp)<option value="{{ $mp->id }}">{{ $mp->nama }}</option>@endforeach
            </select>
        </div>
        <div><label class="lbl">Judul Materi</label><input name="judul" x-model="f.judul" required placeholder="mis. Pengenalan Variabel & Tipe Data" class="inp"></div>
        <div><label class="lbl">Keterangan <span class="font-normal text-gray-400">(opsional)</span></label><textarea name="deskripsi" x-model="f.deskripsi" rows="3" class="inp" placeholder="Materi ini membahas tentang..."></textarea></div>

        <template x-if="editing && f.files && f.files.length">
            <div>
                <label class="lbl">File saat ini <span class="font-normal text-gray-400">(centang untuk dihapus)</span></label>
                <div class="mt-1.5 space-y-1.5">
                    <template x-for="file in f.files" :key="file.id">
                        <label class="flex items-center gap-2 rounded-lg border border-line px-3 py-2 text-sm">
                            <input type="checkbox" name="hapus_file[]" :value="file.id" class="accent-red-600">
                            <span class="truncate" x-text="file.nama_asli"></span>
                        </label>
                    </template>
                </div>
            </div>
        </template>

        <div>
            <label class="lbl" x-text="editing ? 'Tambah file baru' : 'File'"></label>
            <input type="file" name="files[]" multiple class="inp">
            <p class="mt-1.5 text-xs text-gray-400">Bebas: foto, PDF, Excel, Word, dll. Maks 10 MB per file, sampai 10 file sekali upload.</p>
        </div>
    </x-modal>
</div>
@endsection