@extends('layouts.admin')
@section('title','Data Kelas')
@section('content')
<div x-data="{ open:false, editing:null, f:{},
    add(){ this.editing=null; this.f={nama:'',jurusan:''}; this.open=true },
    edit(k){ this.editing=k.id; this.f={nama:k.nama, jurusan:k.jurusan ?? ''}; this.open=true } }">

    <x-page-head title="Data Kelas" sub="Kelola daftar kelas SMK Kosgoro." add="Tambah Kelas" search="Cari kelas..." />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($kelas as $k)
        <div class="bg-white rounded-2xl border-l-4 border-l-maroon border border-gray-100 p-4 shadow-sm">
            <div class="flex justify-between items-start gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-maroon/10 text-maroon flex items-center justify-center"><i class="fa-solid fa-school"></i></div>
                    <div>
                        <h3 class="font-bold">{{ $k->nama }}</h3>
                        <p class="text-sm text-gray-500">{{ $k->jurusan ?? '-' }}</p>
                    </div>
                </div>
                <span class="bg-cream text-maroon text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $k->jadwals_count }} jadwal</span>
            </div>
            <hr class="my-3">
            <x-actions :item="$k->only(['id','nama','jurusan'])" :destroy="route('admin.kelas.destroy', $k)"
                       msg="Hapus kelas ini? Jadwal terkait ikut terhapus." />
        </div>
        @empty
        <x-empty-state icon="fa-school" text="Belum ada data kelas." />
        @endforelse
    </div>

    <x-modal title="Kelas" :store="route('admin.kelas.store')" :update="route('admin.kelas.update','_id_')">
        <div><label class="lbl">Nama Kelas</label><input name="nama" x-model="f.nama" required placeholder="mis. X RPL 1" class="inp"></div>
        <div><label class="lbl">Jurusan</label><input name="jurusan" x-model="f.jurusan" placeholder="mis. RPL" class="inp"></div>
    </x-modal>
</div>
@endsection