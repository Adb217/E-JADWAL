@extends('layouts.admin')
@section('title','Data Mata Pelajaran')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={nama:'',kode:''}; this.open=true },
    edit(m){ this.editing=m.id; this.f={nama:m.nama, kode:m.kode ?? ''}; this.open=true } }">

    <x-page-head title="Data Mata Pelajaran" sub="Kelola daftar mata pelajaran." add="Tambah Mapel" search="Cari mapel..." />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($mapel as $m)
        <div class="bg-white rounded-2xl border-l-4 border-l-gold border border-gray-100 p-4 shadow-sm">
            <div class="flex justify-between items-start gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gold/20 text-yellow-700 flex items-center justify-center"><i class="fa-solid fa-book-open"></i></div>
                    <div>
                        <h3 class="font-bold">{{ $m->nama }}</h3>
                        <p class="text-sm text-gray-500">Kode: {{ $m->kode ?? '-' }}</p>
                    </div>
                </div>
                <span class="bg-cream text-maroon text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $m->jadwals_count }} jadwal</span>
            </div>
            <hr class="my-3">
            <x-actions :item="$m->only(['id','nama','kode'])" :destroy="route('admin.mapel.destroy', $m)"
                       msg="Hapus mapel ini? Jadwal terkait ikut terhapus." />
        </div>
        @empty
        <x-empty-state icon="fa-book-open" text="Belum ada mata pelajaran." />
        @endforelse
    </div>

    <x-modal title="Mapel" :store="route('admin.mapel.store')" :update="route('admin.mapel.update','_id_')">
        <div><label class="lbl">Nama Mata Pelajaran</label><input name="nama" x-model="f.nama" required class="inp"></div>
        <div><label class="lbl">Kode</label><input name="kode" x-model="f.kode" placeholder="mis. MTK" class="inp"></div>
    </x-modal>
</div>
@endsection