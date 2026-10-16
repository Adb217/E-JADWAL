@extends('layouts.viewer')
@section('title','Guru')
@section('content')
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <h1 class="font-display text-2xl font-semibold lg:text-3xl">Daftar Guru</h1>
        <p class="mt-1 text-sm text-gray-500">Status kehadiran hari ini, {{ now()->translatedFormat('l, d F Y') }}.</p>
    </div>
    <form method="GET" class="relative sm:w-80">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama, kode, atau mapel..." class="inp !mt-0 pl-10">
    </form>
</div>

<div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
    @forelse($guru as $g)
    @php $b = \App\Models\KehadiranGuru::BADGE[$g->status_hari_ini]; @endphp
    <div class="card flex gap-3 !p-4">
        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gold-soft text-sm font-semibold text-maroon-dark">{{ $g->inisial }}</span>
        <div class="min-w-0 flex-1">
            <p class="font-semibold leading-snug">{{ $g->nama }}</p>
            <span class="{{ $b['class'] }} mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium">{{ $b['label'] }}</span>
            <div class="mt-2 flex flex-wrap gap-1.5">
                @foreach($g->mapel as $m)<span class="chip">{{ $m->nama }}</span>@endforeach
            </div>
        </div>
        @if($g->kode)<span class="text-xs text-gray-400">{{ $g->kode }}</span>@endif
    </div>
    @empty
    <x-empty-state icon="fa-chalkboard-user" text="Guru tidak ditemukan." />
    @endforelse
</div>
@endsection