@extends('layouts.admin')
@section('title','Data Guru')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={nama:'',nip:'',status:'Aktif',mapel_ids:[]}; this.open=true },
    edit(g){ this.editing=g.id; this.f={...g, nip:g.nip ?? ''}; this.open=true } }">

    <x-page-head title="Data Guru" sub="Kelola daftar tenaga pendidik SMK Kosgoro." add="Tambah Guru" search="Cari Nama / NIP..." />

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($guru as $g)
        <div class="bg-white rounded-2xl border-l-4 {{ $g->status === 'Aktif' ? 'border-l-maroon' : 'border-l-gray-400' }} border border-gray-100 p-4 shadow-sm flex flex-col">
            <div class="flex justify-between items-start gap-2">
                <div>
                    <h3 class="font-bold">{{ $g->nama }}</h3>
                    <p class="text-gray-500 text-sm mb-2">NIP: {{ $g->nip ?? '-' }}</p>
                </div>
                <span class="bg-cream text-maroon text-xs font-semibold px-3 py-1 rounded-full h-fit">{{ $g->status }}</span>
            </div>
            <div class="flex flex-wrap gap-2 mb-3">
                @forelse($g->mapel as $m)
                <span class="bg-cream text-sm px-3 py-1 rounded-full">{{ $m->nama }}</span>
                @empty
                <span class="text-sm text-gray-400">Belum ada mapel</span>
                @endforelse
            </div>
            <hr class="mb-2 mt-auto">
            <x-actions :item="['id'=>$g->id,'nama'=>$g->nama,'nip'=>$g->nip,'status'=>$g->status,'mapel_ids'=>$g->mapel->pluck('id')]"
                       :destroy="route('admin.guru.destroy', $g)" msg="Hapus guru ini beserta jadwal mengajarnya?" />
        </div>
        @empty
        <x-empty-state icon="fa-chalkboard-user" text="Belum ada data guru." />
        @endforelse
    </div>

    <x-modal title="Guru" :store="route('admin.guru.store')" :update="route('admin.guru.update','_id_')">
        <div><label class="lbl">Nama</label><input name="nama" x-model="f.nama" required class="inp"></div>
        <div><label class="lbl">NIP</label><input name="nip" x-model="f.nip" class="inp"></div>
        <div>
            <label class="lbl">Status</label>
            <select name="status" x-model="f.status" class="inp">
                <option>Aktif</option><option>Cuti</option><option>Nonaktif</option>
            </select>
        </div>
        <div>
            <label class="lbl">Mata Pelajaran</label>
            <div class="grid grid-cols-2 gap-2 mt-2">
                @foreach($mapel as $m)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="mapel_ids[]" value="{{ $m->id }}"
                           :checked="f.mapel_ids.map(String).includes('{{ $m->id }}')" class="accent-[#7A0C1E]">{{ $m->nama }}
                </label>
                @endforeach
            </div>
        </div>
    </x-modal>
</div>
@endsection