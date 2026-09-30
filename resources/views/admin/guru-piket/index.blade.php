@extends('layouts.admin')
@section('title','Data Guru Piket')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={guru_id:'',hari:'Senin',lokasi:'Lobi Utama'}; this.open=true },
    edit(p){ this.editing=p.id; this.f={guru_id:p.guru_id, hari:p.hari, lokasi:p.lokasi ?? ''}; this.open=true } }">

    <x-page-head title="Data Guru Piket" sub="Atur guru piket untuk tiap hari." add="Tambah Guru Piket" />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($piket as $p)
        <div class="bg-white rounded-2xl border-l-4 border-l-gold border border-gray-100 p-4 shadow-sm">
            <div class="flex justify-between items-start gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gold flex items-center justify-center font-bold">{{ $p->guru->inisial }}</div>
                    <div>
                        <h3 class="font-bold">{{ $p->guru->nama }}</h3>
                        <p class="text-sm text-gray-500"><i class="fa-solid fa-location-dot mr-1"></i>{{ $p->lokasi ?? '-' }}</p>
                    </div>
                </div>
                <span class="bg-cream text-maroon text-xs font-semibold px-3 py-1 rounded-full">{{ $p->hari }}</span>
            </div>
            <hr class="my-3">
            <x-actions :item="$p->only(['id','guru_id','hari','lokasi'])" :destroy="route('admin.guru-piket.destroy', $p)" msg="Hapus jadwal piket ini?" />
        </div>
        @empty
        <x-empty-state icon="fa-id-card" text="Belum ada jadwal guru piket." />
        @endforelse
    </div>

    <x-modal title="Guru Piket" :store="route('admin.guru-piket.store')" :update="route('admin.guru-piket.update','_id_')">
        <div>
            <label class="lbl">Guru</label>
            <select name="guru_id" x-model="f.guru_id" required class="inp">
                <option value="">Pilih guru</option>
                @foreach($guru as $g)<option value="{{ $g->id }}">{{ $g->nama }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="lbl">Hari</label>
            <select name="hari" x-model="f.hari" class="inp">
                @foreach(App\Models\Jadwal::HARI as $h)<option>{{ $h }}</option>@endforeach
            </select>
        </div>
        <div><label class="lbl">Lokasi Piket</label><input name="lokasi" x-model="f.lokasi" class="inp"></div>
    </x-modal>
</div>
@endsection