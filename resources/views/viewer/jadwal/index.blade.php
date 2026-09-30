@extends('layouts.viewer')
@section('title','Jadwal')
@section('content')
<form method="GET" class="flex flex-col lg:flex-row gap-3 mb-6">
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

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @forelse($jadwal as $j)
    <div class="bg-white rounded-2xl border-l-4 {{ $j->border }} border border-gray-100 p-4 shadow-sm {{ $j->status === 'Selesai' ? 'opacity-50' : '' }}">
        <div class="flex justify-between items-center gap-2 mb-2">
            <span class="font-bold text-lg">{{ $j->mulai }} <span class="text-gray-400 text-sm font-normal">- {{ $j->selesai }}</span></span>
            <span class="{{ $j->badge }} text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $j->status === 'Terjadwal' ? $j->hari : $j->status }}</span>
        </div>
        <h3 class="font-bold">{{ $j->mapel->nama }}</h3>
        <p class="text-gray-500 text-sm mb-2">{{ $j->kelas->nama }}</p>
        <p class="text-sm text-gray-600 flex flex-wrap gap-x-4 gap-y-1">
            <span><i class="fa-solid fa-user w-4 text-center mr-1"></i>{{ $j->guru->nama }}</span>
            <span><i class="fa-solid fa-door-open w-4 text-center mr-1"></i>{{ $j->ruangan->nama }}</span>
        </p>
    </div>
    @empty
    <x-empty-state icon="fa-calendar-xmark" text="Tidak ada jadwal untuk filter ini." />
    @endforelse
</div>
@endsection