@extends('layouts.viewer')
@section('title','Monitoring Jadwal')
@section('content')
<div x-data="{ t: new Date() }" x-init="setInterval(() => t = new Date(), 1000); setTimeout(() => location.reload(), 60000)"
     class="grid gap-6 lg:grid-cols-3 lg:items-start">

    <div class="rounded-xl bg-maroon-dark bg-grid p-7 text-white lg:col-start-3 lg:row-start-1">
        <p class="text-sm text-white/60">Sekarang</p>
        <p class="tnum mt-2 font-display text-6xl font-semibold leading-none"
           x-text="t.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',hourCycle:'h23'}).replace('.',':')">--:--</p>
        <div class="my-4 h-px w-12 bg-gold"></div>
        <p class="text-sm text-white/70"
           x-text="t.toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'})"></p>
    </div>

    <section class="lg:col-span-2 lg:col-start-1 lg:row-span-3 lg:row-start-1">
        <h2 class="mb-4 font-display text-xl font-semibold">Jadwal hari ini</h2>
        <div class="grid gap-3 md:grid-cols-2">
            @forelse($today as $t)
            <article class="card flex gap-4 {{ $t->status === 'Berlangsung' ? 'card-live' : '' }}">
                <div class="w-20 shrink-0 border-r border-line pr-4 text-right">
                    <p class="tnum font-display text-xl font-semibold leading-none">{{ $t->mulai }}</p>
                    <p class="tnum mt-1.5 text-xs text-gray-400">{{ $t->selesai }}</p>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold leading-snug">{{ $t->mapel->nama }}</h3>
                        <span class="{{ $t->badge }} whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium">
                            {{ $t->status === 'Berlangsung' ? 'Berlangsung' : $t->status }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $t->kelas->nama }} · {{ $t->ruangan->nama }}</p>
                    <p class="mt-0.5 text-sm text-gray-500"><i class="fa-regular fa-user mr-1.5 text-xs"></i>{{ $t->guru->nama }}</p>
                </div>
            </article>
            @empty
            <x-empty-state icon="fa-calendar-xmark" text="Tidak ada jadwal tersisa hari ini." />
            @endforelse
        </div>
    </section>

    <section class="lg:col-start-3 lg:row-start-2">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-xl font-semibold">Status ruangan</h2>
            <a href="{{ route('viewer.ruangan.index') }}" class="text-sm font-semibold text-maroon hover:underline">Lihat semua</a>
        </div>
        <div class="card grid grid-cols-2 divide-x divide-line !p-0">
            <div class="p-5">
                <p class="tnum font-display text-4xl font-semibold leading-none">{{ $tersedia }}</p>
                <p class="mt-2 flex items-center gap-2 text-sm text-gray-500"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Tersedia</p>
            </div>
            <div class="p-5">
                <p class="tnum font-display text-4xl font-semibold leading-none">{{ $terpakai }}</p>
                <p class="mt-2 flex items-center gap-2 text-sm text-gray-500"><span class="h-2 w-2 rounded-full bg-maroon"></span>Terpakai</p>
            </div>
        </div>
    </section>

    <section class="lg:col-start-3 lg:row-start-3">
        <h2 class="mb-4 font-display text-xl font-semibold">Guru piket hari ini</h2>
        <div class="space-y-3">
            @forelse($piket as $p)
            <div class="card flex items-center gap-3 !p-4">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-gold-soft text-sm font-semibold text-maroon-dark">{{ $p->guru->inisial }}</span>
                <div class="min-w-0">
                    <p class="truncate font-semibold">{{ $p->guru->nama }}</p>
                    <p class="text-sm text-gray-500"><i class="fa-solid fa-location-dot mr-1 text-xs"></i>{{ $p->lokasi ?? '-' }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada guru piket hari ini.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection