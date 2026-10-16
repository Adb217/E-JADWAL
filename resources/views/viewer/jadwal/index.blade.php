@extends('layouts.viewer')
@section('title','Jadwal')
@section('content')
@php
    $byHari = $jadwal->groupBy('hari');
    $hariList = collect(App\Models\Jadwal::HARI)->filter(fn ($h) => $byHari->has($h))->values();
    $kelasList = $jadwal->pluck('kelas')->unique('id')->sortBy('nama')->values();
@endphp

<form method="GET" class="flex flex-col lg:flex-row gap-3 mb-4">
    <div class="relative flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kelas, mapel, atau guru..."
            class="w-full pl-11 pr-4 py-3 rounded-xl bg-cream border border-gray-200 focus:outline-none focus:ring-2 focus:ring-maroon/40">
    </div>
    <div class="flex gap-3">
        <select name="kelas_id" onchange="this.form.submit()" class="flex-1 lg:w-44 border border-gray-200 rounded-xl p-3 bg-white text-sm">
            <option value="">Semua Kelas</option>
            @foreach($kelas as $k)
            <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
            @endforeach
        </select>
        <select name="hari" onchange="this.form.submit()" class="flex-1 lg:w-44 border border-gray-200 rounded-xl p-3 bg-white text-sm">
            <option value="today" @selected($hari === 'today')>Hari Ini</option>
            <option value="all" @selected($hari === 'all')>Semua Hari</option>
            @foreach(App\Models\Jadwal::HARI as $h)
            <option value="{{ $h }}" @selected($hari === $h)>{{ $h }}</option>
            @endforeach
        </select>
    </div>
</form>

<div x-data="{ hideHari: [], hideKelas: [],
        tog(list, v){ const i = this[list].indexOf(v); i > -1 ? this[list].splice(i, 1) : this[list].push(v) },
        get dirty(){ return this.hideHari.length > 0 || this.hideKelas.length > 0 },
        reset(){ this.hideHari = []; this.hideKelas = [] } }">

    {{-- Chip filter: klik buat sembunyiin / tampilin lagi --}}
    @if($hariList->count() > 1 || $kelasList->count() > 1)
    <div class="mb-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-gray-500">
        @if($hariList->count() > 1)
        <div class="flex flex-wrap items-center gap-1.5">
            <span class="font-semibold text-gray-600">Hari:</span>
            @foreach($hariList as $h)
            <button type="button" @click="tog('hideHari', '{{ $h }}')"
                    :class="hideHari.includes('{{ $h }}') ? 'opacity-40 line-through' : ''"
                    :title="hideHari.includes('{{ $h }}') ? 'Tampilkan lagi' : 'Sembunyikan {{ $h }}'"
                    class="{{ App\Models\Jadwal::warnaHari($h)['chip'] }} inline-flex items-center gap-1.5 rounded-full py-0.5 pl-2.5 pr-2 font-semibold transition">
                {{ $h }}
                <i class="fa-solid text-[10px]" :class="hideHari.includes('{{ $h }}') ? 'fa-plus' : 'fa-xmark'"></i>
            </button>
            @endforeach
        </div>
        @endif

        @if($kelasList->count() > 1)
        <div class="flex flex-wrap items-center gap-1.5">
            <span class="font-semibold text-gray-600">Kelas:</span>
            @foreach($kelasList as $k)
            <button type="button" @click="tog('hideKelas', {{ $k->id }})"
                    :class="hideKelas.includes({{ $k->id }}) ? 'opacity-40 line-through' : ''"
                    :title="hideKelas.includes({{ $k->id }}) ? 'Tampilkan lagi' : 'Sembunyikan {{ $k->nama }}'"
                    class="{{ $k->warna['chip'] }} inline-flex items-center gap-1.5 rounded-md py-0.5 pl-2 pr-1.5 font-semibold transition">
                {{ $k->nama }}
                <i class="fa-solid text-[10px]" :class="hideKelas.includes({{ $k->id }}) ? 'fa-plus' : 'fa-xmark'"></i>
            </button>
            @endforeach
        </div>
        @endif

        <button type="button" x-show="dirty" x-cloak @click="reset()" class="font-semibold text-maroon hover:underline">Reset</button>
    </div>
    @endif

    {{-- Satu container per hari, isinya slider per kelas --}}
    @forelse($hariList as $h)
    @php
        $w = App\Models\Jadwal::warnaHari($h);
        $perKelas = $byHari[$h]->groupBy('kelas_id');
        $ids = $kelasList->pluck('id')->filter(fn ($id) => $perKelas->has($id))->values();
    @endphp
    <section x-show="! hideHari.includes('{{ $h }}') && {{ $ids->toJson() }}.some(id => ! hideKelas.includes(id))"
             class="mb-6 rounded-2xl border {{ $w['card'] }} p-4 sm:p-5">
        <div class="mb-4 flex items-center gap-3">
            <span class="{{ $w['chip'] }} rounded-full px-4 py-1 text-sm font-semibold">{{ $h }}</span>
            <span class="text-xs text-gray-500">{{ $byHari[$h]->count() }} jadwal</span>
        </div>

        <div class="space-y-5">
            @foreach($kelasList as $k)
            @continue(! $perKelas->has($k->id))
            <div x-show="! hideKelas.includes({{ $k->id }})"
                 x-data="{ prev: false, next: false,
                    upd(){ const t = this.$refs.track; this.prev = t.scrollLeft > 4; this.next = t.scrollLeft + t.clientWidth < t.scrollWidth - 4 },
                    go(d){ const t = this.$refs.track; t.scrollBy({ left: d * t.clientWidth * 0.8, behavior: 'smooth' }) } }"
                 x-init="new ResizeObserver(() => upd()).observe($refs.track)">
                <div class="mb-2 flex items-center justify-between">
                    <span class="{{ $k->warna['chip'] }} rounded-md px-2 py-0.5 text-xs font-semibold">{{ $k->nama }}</span>
                    <div class="flex gap-1.5" x-show="prev || next" x-cloak>
                        <button type="button" @click="go(-1)" :disabled="! prev" aria-label="Sebelumnya"
                                class="grid h-7 w-7 place-items-center rounded-full border border-line bg-white text-xs text-gray-500 transition hover:text-maroon disabled:opacity-30">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" @click="go(1)" :disabled="! next" aria-label="Berikutnya"
                                class="grid h-7 w-7 place-items-center rounded-full border border-line bg-white text-xs text-gray-500 transition hover:text-maroon disabled:opacity-30">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div x-ref="track" @scroll.passive="upd()"
                     class="-mx-1 flex snap-x snap-mandatory gap-3 overflow-x-auto scroll-smooth px-1 py-1">
                    @foreach($perKelas[$k->id] as $j)
                    <a href="{{ route('viewer.jadwal.show', $j) }}"
                       class="block w-72 shrink-0 snap-start rounded-xl border border-l-4 border-gray-100 {{ $k->warna['border'] }} bg-white p-4 shadow-sm transition hover:shadow-md sm:w-80 {{ $j->status === 'Selesai' ? 'opacity-50' : '' }} {{ $j->status === 'Berlangsung' ? 'ring-2 ring-maroon/40' : '' }}">
                        <div class="mb-2 flex items-center justify-between gap-2">
                            <span class="text-lg font-bold">{{ $j->mulai }} <span class="text-sm font-normal text-gray-400">- {{ $j->selesai }}</span></span>
                            @if($j->status !== 'Terjadwal')
                            <span class="{{ $j->badge }} whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold">{{ $j->status }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold leading-snug">{{ $j->mapel->nama }}</h3>
                        @if($j->pekan_label)<p class="mt-1"><span class="chip">{{ $j->pekan_label }}</span></p>@endif
                        <p class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                            <span><i class="fa-solid fa-user w-4 text-center mr-1"></i>{{ $j->guru->nama }}</span>
                            <span><i class="fa-solid fa-door-open w-4 text-center mr-1"></i>{{ $j->ruangan_nama }}</span>
                        </p>
                        <p class="mt-3 text-xs font-semibold text-maroon"><i class="fa-solid fa-folder-open mr-1"></i>Lihat detail & materi</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @empty
    <x-empty-state icon="fa-calendar-xmark" text="Tidak ada jadwal untuk filter ini." />
    @endforelse
</div>
@endsection