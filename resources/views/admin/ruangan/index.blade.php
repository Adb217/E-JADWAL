@extends('layouts.admin')
@section('title','Data Ruangan')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={nama:'',tipe:'Kelas Reguler',kapasitas:''}; this.open=true },
    edit(r){ this.editing=r.id; this.f={nama:r.nama, tipe:r.tipe, kapasitas:r.kapasitas ?? ''}; this.open=true } }">

    <x-page-head title="Data Ruangan" sub="Kelola daftar ruangan dan laboratorium." add="Tambah Ruangan" search="Cari ruangan..." />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($ruangan as $r)
        <div class="bg-white rounded-2xl border-l-4 border-l-maroon border border-gray-100 p-4 shadow-sm">
            <div class="flex justify-between items-start gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-maroon/10 text-maroon flex items-center justify-center">
                        <i class="fa-solid {{ $r->tipe === 'Laboratorium Komputer' ? 'fa-laptop-code' : 'fa-door-open' }}"></i>
                    </div>
                    <div>
                        <h3 class="font-bold">{{ $r->nama }}</h3>
                        <p class="text-sm text-gray-500">{{ $r->tipe }}@if($r->kapasitas) • {{ $r->kapasitas }} kursi @endif</p>
                    </div>
                </div>
                <span class="bg-cream text-maroon text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $r->jadwals_count }} jadwal</span>
            </div>
            <hr class="my-3">
            <x-actions :item="$r->only(['id','nama','tipe','kapasitas'])" :destroy="route('admin.ruangan.destroy', $r)"
                       msg="Hapus ruangan ini? Jadwal terkait ikut terhapus." />
        </div>
        @empty
        <x-empty-state icon="fa-door-open" text="Belum ada data ruangan." />
        @endforelse
    </div>

    <x-modal title="Ruangan" :store="route('admin.ruangan.store')" :update="route('admin.ruangan.update','_id_')">
        <div><label class="lbl">Nama Ruangan</label><input name="nama" x-model="f.nama" required class="inp"></div>
        <div>
            <label class="lbl">Tipe</label>
            <select name="tipe" x-model="f.tipe" class="inp">
                @foreach(App\Models\Ruangan::TIPE as $t)<option>{{ $t }}</option>@endforeach
            </select>
        </div>
        <div><label class="lbl">Kapasitas</label><input type="number" min="1" name="kapasitas" x-model="f.kapasitas" class="inp"></div>
    </x-modal>
</div>
@endsection