@extends('layouts.viewer')
@section('title', 'Materi ' . $kelas->nama)
@section('content')
<x-back :href="$kelas->angkatan ? route('viewer.mapel.kelas', $kelas->angkatan) : route('viewer.mapel.index')" label="Pilih kelas" />

<div x-data="{ m: 'all' }">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-semibold lg:text-3xl">Materi {{ $kelas->nama }}</h1>
            <p class="mt-1 text-sm text-gray-500">Angkatan {{ $kelas->angkatan }} · {{ $mapel->count() }} mata pelajaran</p>
        </div>
        <a href="{{ route('viewer.jadwal.index', ['kelas_id' => $kelas->id, 'hari' => 'all']) }}" class="btn-ghost self-start">
            <i class="fa-solid fa-calendar-days"></i>Lihat jadwal kelas
        </a>
    </div>

    @if($mapel->isEmpty())
        <x-empty-state icon="fa-book-open" text="Belum ada mapel di jadwal kelas ini." />
    @else
    <div class="mb-6 flex flex-wrap gap-2">
        <button type="button" @click="m = 'all'"
                :class="m === 'all' ? 'border-maroon bg-maroon text-white' : 'border-line bg-white text-gray-600 hover:border-maroon/40'"
                class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition">Semua</button>
        @foreach($mapel as $x)
        @php $n = isset($materi[$x->mapel->id]) ? $materi[$x->mapel->id]->count() : 0; @endphp
        <button type="button" @click="m = {{ $x->mapel->id }}"
                :class="m === {{ $x->mapel->id }} ? 'border-maroon bg-maroon text-white' : 'border-line bg-white text-gray-600 hover:border-maroon/40'"
                class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition">
            {{ $x->mapel->nama }} <span class="ml-1 text-xs opacity-70">{{ $n }}</span>
        </button>
        @endforeach
    </div>

    @foreach($mapel as $x)
    @php
        $list = $materi[$x->mapel->id] ?? collect();
        $cond = "m === {$x->mapel->id}" . ($list->isNotEmpty() ? " || m === 'all'" : '');
    @endphp
    <section class="mb-8" x-show="{{ $cond }}" x-cloak>
        <div class="mb-3">
            <h2 class="font-display text-xl font-semibold">{{ $x->mapel->nama }}</h2>
            <p class="text-sm text-gray-500"><i class="fa-regular fa-user mr-1.5 text-xs"></i>{{ $x->guru->pluck('nama')->join(', ') }}</p>
        </div>
        @include('viewer._materi', ['materi' => $list])
    </section>
    @endforeach

    @if($materi->isEmpty())
    <div x-show="m === 'all'" x-cloak>
        <x-empty-state icon="fa-folder-open" text="Belum ada materi untuk kelas ini." />
    </div>
    @endif
    @endif
</div>
@endsection