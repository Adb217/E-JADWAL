@extends('layouts.viewer')
@section('title','Mapel')
@section('content')
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold lg:text-3xl">Mata Pelajaran</h1>
    <p class="mt-1 text-sm text-gray-500">Pilih angkatan{{ $tahunAjaran ? ' · TA ' . $tahunAjaran : '' }}.</p>
</div>
<div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
    @forelse($angkatan as $a)
    <x-pick-card :href="route('viewer.mapel.kelas', $a['angkatan'])" icon="fa-graduation-cap"
                 :title="'Angkatan ' . $a['angkatan']" :sub="'Kelas ' . \App\Models\Kelas::ROMAWI[$a['tingkat']]" />
    @empty
    <x-empty-state icon="fa-graduation-cap" text="Belum ada data angkatan." />
    @endforelse
</div>
@endsection