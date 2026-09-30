@extends('layouts.admin')
@section('title','Info Perubahan')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={tipe:'guru_berhalangan',judul:'',dari:'',ke:'',alasan:'',status:'Menunggu'}; this.open=true },
    edit(p){ this.editing=p.id; this.f={...p, dari:p.dari ?? '', ke:p.ke ?? ''}; this.open=true } }">

    <x-page-head title="Info Perubahan" sub="Umumkan perubahan guru, ruangan, dan jadwal ke halaman monitoring." add="Tambah Info" />

    <div class="grid gap-4 md:grid-cols-2">
        @forelse($perubahan as $p)
        <div class="bg-cream rounded-2xl border-l-4 {{ $p->border }} p-4">
            <div class="flex justify-between items-start gap-2 mb-2">
                <h3 class="font-bold text-lg flex items-center gap-2"><i class="fa-solid {{ $p->icon }} text-maroon"></i>{{ $p->judul }}</h3>
                <span class="{{ $p->badge }} text-white text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $p->status }}</span>
            </div>
            @if($p->dari || $p->ke)
            <div class="bg-white rounded-xl p-3 flex justify-between items-center gap-2 mb-2 text-sm">
                <div><p class="text-gray-400 text-xs font-semibold">{{ $p->meta['from'] }}</p><p class="font-bold">{{ $p->dari ?? '-' }}</p></div>
                <i class="fa-solid fa-arrow-right text-gray-400"></i>
                <div class="text-right"><p class="text-gray-400 text-xs font-semibold">{{ $p->meta['to'] }}</p><p class="font-bold text-maroon">{{ $p->ke ?? '-' }}</p></div>
            </div>
            @endif
            <p class="text-sm text-gray-600 mb-2"><i class="fa-solid fa-circle-info mr-1"></i>Alasan: {{ $p->alasan }}</p>
            <p class="text-xs text-gray-400 mb-2">{{ $p->created_at->diffForHumans() }}</p>
            <x-actions :item="$p->only(['id','tipe','judul','dari','ke','alasan','status'])" :destroy="route('admin.perubahan.destroy', $p)" msg="Hapus info perubahan ini?" />
        </div>
        @empty
        <x-empty-state icon="fa-bell-slash" text="Belum ada info perubahan." />
        @endforelse
    </div>

    <x-modal title="Info Perubahan" :store="route('admin.perubahan.store')" :update="route('admin.perubahan.update','_id_')">
        <div>
            <label class="lbl">Jenis Perubahan</label>
            <select name="tipe" x-model="f.tipe" class="inp">
                @foreach(App\Models\Perubahan::TIPE as $key => $t)<option value="{{ $key }}">{{ $t['label'] }}</option>@endforeach
            </select>
        </div>
        <div><label class="lbl">Judul</label><input name="judul" x-model="f.judul" required class="inp"></div>
        <div class="grid grid-cols-2 gap-3">
            <div><label class="lbl">Dari</label><input name="dari" x-model="f.dari" class="inp"></div>
            <div><label class="lbl">Menjadi</label><input name="ke" x-model="f.ke" class="inp"></div>
        </div>
        <div><label class="lbl">Alasan</label><textarea name="alasan" x-model="f.alasan" required rows="3" class="inp"></textarea></div>
        <div>
            <label class="lbl">Status</label>
            <select name="status" x-model="f.status" class="inp">
                @foreach(App\Models\Perubahan::STATUS as $s)<option>{{ $s }}</option>@endforeach
            </select>
        </div>
    </x-modal>
</div>
@endsection