@extends('layouts.viewer')
@section('title','Pilih Kelas')
@section('content')
<x-back :href="route('viewer.mapel.index')" label="Pilih angkatan" />
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold lg:text-3xl">Angkatan {{ $angkatan }}</h1>
    <p class="mt-1 text-sm text-gray-500">Pilih kelas.</p>
</div>
<div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
    @foreach($kelas as $k)
        <x-pick-card :href="route('viewer.mapel.materi', $k)" icon="fa-school" :title="$k->nama" :sub="$k->jurusan" />
    @endforeach
</div>
@endsection